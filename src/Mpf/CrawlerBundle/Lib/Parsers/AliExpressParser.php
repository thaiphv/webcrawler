<?php

namespace Mpf\CrawlerBundle\Lib\Parsers;

use Symfony\Component\DomCrawler\Crawler;

class AliExpressParser extends Parser {
  public function getProductLinksOnListingPage() {
    $productCssLocators = array(
        'div.detail h3 a',
        'ul#list-items li .detail h3 a',
        'ul#list-items li .detail h2 a',
        'div.info h3 a.product',
        'div.info h3 a.history-item',
    );

    $crawler = $this->getCrawler(self::LISTING_PAGE);

    foreach ($productCssLocators as $locator) {
      $links = $crawler->filter($locator)->each(function(Crawler $node, $i) {
        return trim($node->attr('href'));
      });
      if (count($links)) {
        return $links;
      }
    }

    return array();
  }

  public function getNextPageLinkOnListingPage() {
    $nextListingPageCssLocators = array(
        'div.ui-pagination-navi a.ui-pagination-next',
        'span#new-list-pg a.pg-next-btn',
        'a.page-next'
    );

    $crawler = $this->getCrawler(self::LISTING_PAGE);

    foreach ($nextListingPageCssLocators as $locator) {
      $linkNode = $crawler->filter($locator);
      if ($linkNode->count() == 1) {
        $class = $linkNode->attr('class');
        $link = $linkNode->attr('href');
        if (strpos($class, 'pg-next-btn-disable') === false) {
          return $link;
        }
      }
    }

    return '';
  }

  public function getProductNameOnProductPage() {
    $cssLocators = array(
        'h1.product-name',
        'h1#product-name'
    );

    $crawler = $this->getCrawler(self::PRODUCT_PAGE);

    foreach ($cssLocators as $locator) {
      $node = $crawler->filter($locator);
      if ($node->count() == 1) {
        return $node->text();
      }
    }
    return '';
  }

  public function getProductPriceOnProductPage() {
    $cssLocators = array(
        'span#sku-price',
        'h1#product-name'
    );

    $crawler = $this->getCrawler(self::PRODUCT_PAGE);

    foreach ($cssLocators as $locator) {
      $node = $crawler->filter($locator);
      if ($node->count() == 1) {
        if (!$node->attr('value')) {
          return $node->attr('value');
        }
        return $node->text();
      }
    }
    return '';
  }

  public function getProductDescriptionOnProductPage() {
    $crawler = $this->getCrawler(self::PRODUCT_PAGE);

    $crawlerNode = $crawler->filter('div#product-desc');
    if ($crawlerNode->count() == 1) {
      return $crawlerNode->html();
    }
  }

  public function getProductImagesOnProductPage() {
    $html = $this->getHtml(self::PRODUCT_PAGE);
    $pattern = '/imageBigViewURL=\[(.*)\]/sU';
    if (preg_match($pattern, $html, $match)) {
      $arrayTxt = str_replace(array("\r", "\n", "\t", " "), '', $match[1]);
      $imageArray = explode(',', $arrayTxt);
      foreach ($imageArray as &$item) {
        $item = trim($item, "\"");
      }
      return $imageArray;
    }
    return array();
  }

  public function getProductDescriptionLinkOnProductPage() {
    $html = $this->getHtml(self::PRODUCT_PAGE);
    $pattern = '/window\.runParams\.descUrl="(.*)";/U';
    if (preg_match($pattern, $html, $match)) {
      return $match[1];
    }
    return '';
  }

  public function mergeWithProductDescriptionPage($html) {
    $descriptionHtml = $this->getHtml(self::PRODUCT_DESCRIPTION_PAGE);

    $doc = new \DOMDocument();
    @$doc->loadHTML($descriptionHtml);
    $nodes = $doc->getElementsByTagName('body');
    $bodyNode = $nodes->item(0);

    $doc2 = new \DOMDocument();
    $doc2->loadHTML($html);
    $xpath = new \DOMXPath($doc2);

    // Remove current place holder DIV
    $nodes = $xpath->query('//div[@id="custom-description"]/div');
    if ($nodes->length == 0) {
      return $html;
    }
    $node = $nodes->item(0);
    while($node->childNodes->length) {
      $node->removeChild($node->firstChild);
    }

    // Replace by the new content
    foreach ($bodyNode->childNodes as $child) {
        $importedNode = $doc2->importNode($child, true);
        $node->appendChild($importedNode);
    }

    $newHtml = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $doc2->saveHTML()));

    return $newHtml;
  }

  public function getImagesInProductDescriptionPage() {
    $crawler = $this->getCrawler(self::PRODUCT_DESCRIPTION_PAGE);
    $imageLinks = $crawler->filter('img')->each(function(Crawler $node, $i) {
      return trim($node->attr('src'));
    });
    var_dump($imageLinks); die;
    return $imageLinks;
  }

}