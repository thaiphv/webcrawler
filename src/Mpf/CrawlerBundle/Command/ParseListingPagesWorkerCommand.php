<?php

namespace Mpf\CrawlerBundle\Command;

use Mpf\CrawlerBundle\Entity\Task;
use Mpf\CrawlerBundle\Lib\NetworkUtil\AngryCurl;
use Mpf\CrawlerBundle\Services\SettingManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseListingPagesWorkerCommand extends ContainerAwareCommand {
  const COMMAND_NAME = 'Listing Page Parser';
  const MAX_CONCURRENT_CRAWLER = 10;

  protected $output;
  protected $sm;
  protected $proxyList;
  protected $userAgentList;
  protected $ac;
  protected $parserClass;
  protected $page;
  protected $currentTask;
  protected $queueRepo;
  protected $logger;

  protected function configure()
  {
    $this
        ->setName('mpf:parse-listing-pages')
        ->setDescription('Parse listing pages to fetch product URLs')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    set_time_limit(0);
    $output->writeln("<info>--- " . self::COMMAND_NAME . " ---</info>");

    $this->output = $output;
    $this->sm = $this->getContainer()->get('setting_manager');
    $this->proxyList = $this->getContainer()->get('doctrine')->getRepository('MpfCrawlerBundle:Proxy')->getProxyList();
    $this->userAgentList = $this->getContainer()->get('doctrine')->getRepository('MpfCrawlerBundle:UserAgent')->getUserAgentList();
    $this->queueRepo = $this->getContainer()->get('doctrine')->getRepository('MpfCrawlerBundle:Queue');
    $this->logger = $this->getContainer()->get('task_logger');

    $taskRepo = $this->getContainer()->get('doctrine')->getRepository('MpfCrawlerBundle:Task');
    $tasks = $taskRepo->getActiveTasks();

    foreach ($tasks as $task) {
      $this->executeTask($task);
    }

    $output->writeln("<info>--- DONE ---</info>");
  }

  protected function executeTask(Task $task) {
    $this->currentTask = $task;
    $this->log("Performing task: " . $task->getName());

    if ($task->getDirectUrls()) {
      $this->log("Queuing direct product URLs");
      $text = $task->getDirectUrls();
      $urls = explode("\n", $text);
      foreach ($urls as $url) {
        $this->log("Queuing product page {$url}");
        $this->queueRepo->queueJob($url, $task);
      }
      return;
    }

    $this->log("Parser class: " . $task->getParser()->getClassName());

    $this->parserClass = "\\Mpf\\CrawlerBundle\\Lib\\Parsers\\" . $task->getParser()->getClassName();

    $this->page = 1;
    $this->ac = new AngryCurl(array($this, 'parseResponse'), true);
    if (count($this->proxyList)) {
      $this->ac->load_proxy_list($this->proxyList, self::MAX_CONCURRENT_CRAWLER, 'http', 'http://www.google.com.au');
    }
    $this->ac->load_useragent_list($this->userAgentList);
    if ($task->getUrl()) {
      $this->log("Queuing first page " . $task->getUrl());
      $this->ac->get($task->getUrl());
      $this->ac->execute(self::MAX_CONCURRENT_CRAWLER);
      $this->ac->flush_requests();
    } else {
      $this->log("Invalid task config: no URL assigned");
    }
    foreach (AngryCurl::$debug_info as $message) {
      $this->log($message);
    }
    AngryCurl::$debug_info = array();
  }

  public function parseResponse($response, $info, $request) {
    if ($info['http_code'] != 200) {
      $this->log('Unable to parse ' . $info['url'] . ' with code ' . $info['http_code']);
      return;
    }
    try {
      /** @var \Mpf\CrawlerBundle\Lib\Parsers\Parser $parser */
      $parser = new $this->parserClass();
    } catch (\Exception $e) {
      $this->log("Parser class {$this->parserClass} does not exist");
      return;
    }
    $parser->parseListingPage($response);

    $nextPageUrl = $parser->getNextPageLinkOnListingPage();
    if ($this->page < $this->sm->getSetting(SettingManager::KEY_MAX_LISTING_PAGES)->getValue() && $nextPageUrl) {
      $this->log("Queuing next listing page {$nextPageUrl}");
      $this->ac->get($nextPageUrl);
      $this->page++;
    }

    $productUrls = $parser->getProductLinksOnListingPage($response);
    foreach ($productUrls as $url) {
      $this->log("Queuing product page {$url}");
      $this->queueRepo->queueJob($url, $this->currentTask);
    }
  }

  protected function log($message) {
    $this->output->writeln($message);
    $this->logger->log($message);
  }
}