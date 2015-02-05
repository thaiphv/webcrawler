<?php

namespace Mpf\CrawlerBundle\Lib\Parsers;

use Symfony\Component\DomCrawler\Crawler;

class EbayParser extends Parser {

  public function getProductLinksOnListingPage() {
    $productCssLocators = array(
        'h3.lvtitle a.vip'
    );

    foreach ($productCssLocators as $locator) {
      $links = $this->crawler->filter($locator)->each(function(Crawler $node, $i) {
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
        '#Pagination a.next'
    );

    foreach ($nextListingPageCssLocators as $locator) {
      $linkNode = $this->crawler->filter($locator);
      if ($linkNode->count() == 1) {
        return $linkNode->attr('href');
      }
    }

    return '';
  }

  public function getProductNameOnProductPage() {

  }

  public function getProductPriceOnProductPage() {

  }

  public function getProductDescriptionOnProductPage() {

  }

  public function getProductImagesOnProductPage() {

  }

  public function getProductDescriptionLinkOnProductPage() {

  }

  public function mergeProductDescription($html, $newHtml) {

  }
}