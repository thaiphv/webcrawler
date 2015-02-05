<?php

namespace Mpf\CrawlerBundle\Command;

use Doctrine\Common\Util\Debug;
use Mpf\CrawlerBundle\Entity\Image;
use Mpf\CrawlerBundle\Entity\Product;
use Mpf\CrawlerBundle\Entity\Queue;
use Mpf\CrawlerBundle\Entity\Task;
use Mpf\CrawlerBundle\Lib\FileStorage\LocalFolder;
use Mpf\CrawlerBundle\Lib\NetworkUtil\AngryCurl;
use Mpf\CrawlerBundle\Lib\Parsers\Parser;
use Mpf\CrawlerBundle\Lib\Tools;
use Mpf\CrawlerBundle\Services\SettingManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ParseProductPagesWorkerCommand extends ContainerAwareCommand {
  const COMMAND_NAME = 'Product Page Parser';
  const MAX_CONCURRENT_CRAWLERS = 10;

  protected $output;
  protected $proxyList;
  protected $userAgentList;
  protected $ac;
  protected $queueRepo;
  protected $productRepo;
  protected $imageRepo;
  protected $logger;
  protected $parserInfo;
  protected $doctrine;
  protected $parserClass;
  protected $fileStorage;
  protected $jobs;
  protected $count;
  protected $newProductCount;
  protected $maxJobPerExecution;

  protected function configure()
  {
    $this
        ->setName('mpf:parse-product-pages')
        ->setDescription('Parse product pages, fetch description and image and persist to local DB')
        ->addOption('dump-parsers', null, InputOption::VALUE_NONE, 'Dump list of parsers with ID')
        ->addOption('parser-id', null, InputOption::VALUE_REQUIRED, 'Parser ID', -1)
    ;
  }

  protected function dumpParsers() {
    $repo = $this->doctrine->getRepository('MpfCrawlerBundle:Parser');
    $parsers = $repo->findAll();

    $this->output->writeln("<info>Available Parsers</info>");
    $this->output->writeln(sprintf("%-5s%-20s", "Id", "Name"));
    foreach ($parsers as $parser) {
      /** @var $parser \Mpf\CrawlerBundle\Entity\Parser */
      $this->output->writeln(sprintf("%-5s%-20s", $parser->getId(), $parser->getName()));
    }
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $this->output = $output;
    $this->doctrine = $this->getContainer()->get('doctrine');

    if ($input->getOption('dump-parsers')) {
      $this->dumpParsers();
      return;
    }

    if (($parserId = $input->getOption('parser-id')) == -1) {
      $output->writeln("<error>Please specify a parser ID</error>");
      return;
    }

    $repo = $this->doctrine->getRepository('MpfCrawlerBundle:Parser');
    if (!($this->parserInfo = $repo->find($parserId))) {
      $output->writeln("<error>Parser ID " . $parserId . " does not exists</error>");
      return;
    }

    set_time_limit(0);
    $output->writeln("<info>--- " . self::COMMAND_NAME . " ---</info>");

    $sm = $this->getContainer()->get('setting_manager');
    $this->maxJobPerExecution = $sm->getSetting(SettingManager::KEY_MAX_PRODUCT_PAGES)->getValue();
    $this->count = 0;
    $this->newProductCount = 0;
    $this->proxyList = $this->doctrine->getRepository('MpfCrawlerBundle:Proxy')->getProxyList();
    $this->userAgentList = $this->doctrine->getRepository('MpfCrawlerBundle:UserAgent')->getUserAgentList();
    $this->queueRepo = $this->doctrine->getRepository('MpfCrawlerBundle:Queue');
    $this->productRepo = $this->doctrine->getRepository('MpfCrawlerBundle:Product');
    $this->imageRepo = $this->doctrine->getRepository('MpfCrawlerBundle:Image');
    $this->logger = $this->getContainer()->get('task_logger');
    $this->parserClass = "\\Mpf\\CrawlerBundle\\Lib\\Parsers\\" . $this->parserInfo->getClassName();

    $imageFolderPath = $this->getContainer()->get('kernel')->getRootDir() . '/../web/images';
    $frontendPath = '/images';
    $this->fileStorage = new LocalFolder($imageFolderPath, $frontendPath);

    $this->executeTasks();

    $this->log('--- Total new products fetched and stored: ' . $this->newProductCount);
    $this->log('--- Total products updated: ' . ($this->count - $this->newProductCount));

    $output->writeln("<info>--- DONE ---</info>");
  }

  protected function executeTasks() {
    $this->ac = new AngryCurl(array($this, 'parseResponse'), true);
    if (count($this->proxyList)) {
      $this->ac->load_proxy_list($this->proxyList, self::MAX_CONCURRENT_CRAWLERS, 'http', 'http://www.google.com.au');
    }
    $this->ac->load_useragent_list($this->userAgentList);

    while (true) {
      $jobs = $this->queueRepo->getJobs($this->parserInfo, self::MAX_CONCURRENT_CRAWLERS);
      if (count($jobs) == 0) {
        $this->log("No more jobs queued");
        break;
      }
      $this->jobs = array();
      foreach ($jobs as $job) {
        /** @var \Mpf\CrawlerBundle\Entity\Queue $job */
        $this->log("Processing product page " . $job->getUrl());
        $this->ac->get($job->getUrl() . '#' . $job->getId());
        $this->jobs[$job->getId()] = $job;
        $this->count++;
      }
      $this->ac->execute(self::MAX_CONCURRENT_CRAWLERS);
      $this->ac->flush_requests();
      // Remove jobs from queue
      foreach ($jobs as $job) {
        $this->queueRepo->removeJob($job);
      }

      // Logging AC activities
      foreach (AngryCurl::$debug_info as $message) {
        $this->log($message);
      }
      AngryCurl::$debug_info = array();

      // Terminate the execution loop if number of products has exceeded
      if ($this->count >= $this->maxJobPerExecution) {
        break;
      }
    }
  }

  public function parseResponse($response, $info, $request) {
    $this->log("Handling query response for URL " . $info['url']);

    if ($info['http_code'] != 200) {
      $this->log('Unable to parse ' . ' with code ' . $info['http_code']);
      return;
    }
    try {
      /** @var \Mpf\CrawlerBundle\Lib\Parsers\Parser $parser */
      $parser = new $this->parserClass();
    } catch (\Exception $e) {
      $this->log("Parser class {$this->parserClass} does not exist");
      return;
    }

    $parts = explode('#', $request->url);
    $jobId = array_pop($parts);
    /** @var \Mpf\CrawlerBundle\Entity\Queue $job */
    $job = $this->jobs[$jobId];
    $em = $this->doctrine->getManager();

    if (($product = $job->getProduct())) {
      if ($job->getAction() == Queue::ACTION_UPDATE_DESCRIPTION) {
        $this->log("Updating custom product description for product ID " . $product->getId());
        $parser->parseProductDescriptionPage($response);

        // Update description
        $newDescription = $parser->mergeWithProductDescriptionPage($product->getDescription());
        $product->setDescription($newDescription);
        $em->persist($product);
        $em->flush();

        // Get image URLs in description
        $imageUrls = $parser->getImagesInProductDescriptionPage();
        foreach ($imageUrls as $imageUrl) {
          $this->log("Queuing job to download a description image for product ID " . $product->getId());
          $this->queueRepo->queueJob($imageUrl, $job->getTask(), $product, Queue::ACTION_DOWNLOAD_DESC_IMAGE);
        }
      } elseif ($job->getAction() == Queue::ACTION_DOWNLOAD_MAIN_IMAGE) {
        $this->log("Downloading main image for product ID " . $product->getId());
        $imageEntity = $this->fileStorage->storeFile($info['url'], $response);
        $imageEntity->setType(Image::IMAGE_TYPE_MAIN);
        $em->persist($imageEntity);
        $em->flush();
      } elseif ($job->getAction() == Queue::ACTION_DOWNLOAD_SUB_IMAGE) {
        $this->log("Downloading subordinate image for product ID " . $product->getId());
        $imageEntity = $this->fileStorage->storeFile($info['url'], $response);
        $imageEntity->setType(Image::IMAGE_TYPE_SUB);
        $em->persist($imageEntity);
        $em->flush();
      } elseif ($job->getAction() == Queue::ACTION_DOWNLOAD_DESC_IMAGE) {
        $this->log("Downloading product description image for product ID " . $product->getId());
        $imageEntity = $this->fileStorage->storeFile($info['url'], $response);
        $imageEntity->setType(Image::IMAGE_TYPE_DESC);
        $em->persist($imageEntity);
        $em->flush();

        // Update image URL in product description
        $description = $product->getDescription();
        $description = str_replace($info['url'], $imageEntity->getPath, $description);
        $product->setDescription($description);
        $em->persist($product);
        $em->flush();
      }
      return;
    }

    $parser->parseProductPage($response);

    $productData = array();
    $productData['name'] = $parser->getProductNameOnProductPage();
    $productData['slug'] = Tools::slugify($productData['name']);
    $productData['price'] = $parser->getProductPriceOnProductPage();
    $productData['description'] = $parser->getProductDescriptionOnProductPage();
    $productData['meta_description'] = $parser->getMetaDescriptionOnProductPage();
    $productData['meta_keywords'] = $parser->getMetaKeywordsOnProductPage();
    $productData['url'] = $info['url'];

    $productOriginalId = Parser::generateOriginalIdFromUrl($info['url']);
    $product = $this->productRepo->findOneByOriginalId($productOriginalId);
    if (!$product) {
      $isNew = true;
      $productData['task'] = $job->getTask();
      $productData['original_id'] = $productOriginalId;
      $product = $this->productRepo->createProduct($productData);
      $this->newProductCount++;
    } else {
      $isNew = false;
      $this->productRepo->updateProduct($product, $productData);
    }

    if ($isNew) {
      $imageUrls = $parser->getProductImagesOnProductPage();
      foreach ($imageUrls as $i => $imageUrl) {
        $imageEntity = $this->imageRepo->findBy(array('url' => $imageUrl));
        if ($imageEntity) {

        }
        $this->log("Queuing job to download an image for product ID " . $product->getId());
        if ($i == 0) {
          $this->queueRepo->queueJob($imageUrl, $job->getTask(), $product, Queue::ACTION_DOWNLOAD_MAIN_IMAGE);
        } else {
          $this->queueRepo->queueJob($imageUrl, $job->getTask(), $product, Queue::ACTION_DOWNLOAD_SUB_IMAGE);
        }
      }
    }

    $productDescriptionLink = $parser->getProductDescriptionLinkOnProductPage();
    if ($productDescriptionLink) {
      $this->log("Queuing job to fetch custom product description for product ID " . $product->getId());
      $this->queueRepo->queueJob($productDescriptionLink, $job->getTask(), $product, Queue::ACTION_UPDATE_DESCRIPTION);
    }
  }

  protected function log($message) {
    $this->output->writeln($message);
    $this->logger->log($message);
  }
}