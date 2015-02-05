<?php

namespace Mpf\CrawlerBundle\Lib\Parsers;

use Symfony\Component\DomCrawler\Crawler;

abstract class Parser {
  const ALIEXPRESS_URL = 'aliexpress.com';
  const EBAY_URL = 'ebay.com';

  const LISTING_PAGE = 'listing';
  const PRODUCT_PAGE = 'product';
  const PRODUCT_DESCRIPTION_PAGE = 'product_description';

  protected $crawlers = array();
  protected $htmlPages = array();

  public function parseListingPage($html) {
    $this->htmlPages[self::LISTING_PAGE] = $html;
  }

  public function parseProductPage($html) {
    $this->htmlPages[self::PRODUCT_PAGE] = $html;
  }

  public function parseProductDescriptionPage($html) {
    $pattern = '/window.productDescription=\'(.*)\';/s';
    if (!preg_match($pattern, $html, $match)) {
      throw new \Exception('Invalid product description page');
    }
    $this->htmlPages[self::PRODUCT_DESCRIPTION_PAGE] = $match[1];
  }

  protected function getCrawler($page) {
    if (!isset($this->crawlers[$page])) {
      if (!isset($this->htmlPages[$page])) {
        throw new \Exception('HTML not found');
      }
      $this->crawlers[$page] = new Crawler($this->htmlPages[$page]);
    }
    return $this->crawlers[$page];
  }

  protected function getHtml($page) {
    if (!isset($this->htmlPages[$page])) {
      throw new \Exception('HTML not found');
    }
    return $this->htmlPages[$page];
  }

  static public function generateOriginalIdFromUrl($url) {
    $urlInfo = parse_url(strtolower($url));
    if (!$urlInfo['host']) {
      throw new \Exception('Invalid URL given: ' . $url);
    }

    if (strpos($urlInfo['host'], self::ALIEXPRESS_URL) !== false) {
      $filename = pathinfo($urlInfo['path'], PATHINFO_FILENAME);
      $parts = explode('_', $filename);
      if (!$filename || count($parts) != 2) {
        throw new \Exception('Invalid URL given: ' . $url);
      }
      return self::ALIEXPRESS_URL . '_' . $parts[1];
    }
  }

  public function getMetaDescriptionOnProductPage() {
    $crawler = $this->getCrawler(self::PRODUCT_PAGE);
    $crawlerNode = $crawler->filter('meta[name="description"]');
    if ($crawlerNode->count() == 1) {
      return $crawlerNode->attr('content');
    }
  }

  public function getMetaKeywordsOnProductPage() {
    $crawler = $this->getCrawler(self::PRODUCT_PAGE);
    $crawlerNode = $crawler->filter('meta[name="keywords"]');
    if ($crawlerNode->count() == 1) {
      return $crawlerNode->attr('content');
    }
  }

  abstract public function getProductLinksOnListingPage();
  abstract public function getNextPageLinkOnListingPage();
  abstract public function getProductNameOnProductPage();
  abstract public function getProductPriceOnProductPage();
  abstract public function getProductDescriptionOnProductPage();
  abstract public function getProductImagesOnProductPage();
  abstract public function getProductDescriptionLinkOnProductPage();
  abstract public function mergeWithProductDescriptionPage($html);
  abstract public function getImagesInProductDescriptionPage();
}