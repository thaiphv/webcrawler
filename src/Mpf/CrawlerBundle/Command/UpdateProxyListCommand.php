<?php

namespace Mpf\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class UpdateProxyListCommand extends ContainerAwareCommand {
  const COMMAND_NAME = 'Update Proxy List';
  const PROXY_LIST_URL = 'http://www.cool-proxy.net/proxies/http_proxy_list/sort:score/direction:desc';
  const MAX_PAGE = 5;

  protected function configure()
  {
    $this
        ->setName('mpf:update-proxy-list')
        ->setDescription('Update latest proxy list from http://www.cool-proxy.net/')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $output->writeln("<info>" . self::COMMAND_NAME . "</info>");

    $repo = $this->getContainer()->get('doctrine')->getRepository('MpfCrawlerBundle:Proxy');

    $page = 1;
    $url = self::PROXY_LIST_URL;

    while (true) {
      $output->writeln("Parsing URL {$url}");

      $html = file_get_contents($url);
      $crawler = new Crawler($html);

      foreach ($crawler->filter('table tr td[style="text-align:left; font-weight:bold;"]') as $element) {
        /** @var \DomElement $element */
        $parts = explode('"', $element->textContent);
        if (count($parts) == 3 && $parts[0] == 'document.write(Base64.decode(str_rot13(' && $parts[2] == ')))') {
          $ip = base64_decode(str_rot13($parts[1]));
        } else {
          continue;
        }

        $nextSiblingElement = $element->nextSibling->nextSibling;
        $port = $nextSiblingElement->textContent;

        $nextSiblingElement = $nextSiblingElement->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling;

        $altText = $nextSiblingElement->firstChild->getAttribute('alt');
        if (preg_match("/([1-5]+) star proxy/", $altText, $match)) {
          $rank = $match[1];
        } else {
          $rank = 1;
        }

        $output->writeln("Found proxy {$ip}:{$port} - {$rank} star(s), adding or updating to DB...");
        $repo->addOrUpdateProxy($ip, $port, $rank);
      }

      $nextPageNode = $crawler->filter('span.current + span > a');
      if ($nextPageNode->count()) {
        $url = $this->getNextUrl($url, $nextPageNode->attr('href'));
      } else {
        break;
      }

      if (++$page > self::MAX_PAGE) {
        break;
      }
    }

    $output->writeln('<info>DONE</info>');
  }

  protected function getNextUrl($url, $path) {
    $urlInfo = parse_url($url);
    return $urlInfo['scheme'] . '://' . $urlInfo['host'] . $path;
  }

}