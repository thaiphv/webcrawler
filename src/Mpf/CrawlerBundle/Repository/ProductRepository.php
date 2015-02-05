<?php

namespace Mpf\CrawlerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Mpf\CrawlerBundle\Entity\Product;

class ProductRepository extends EntityRepository {
  public function findOneByUrl($url) {
    $urlHash = Product::hash($url);
    return $this->findOneBy(array('urlHash' => $urlHash));
  }

  public function findOneByOriginalId($originalId) {
    return $this->findOneBy(array('originalId' => $originalId));
  }

  public function createProduct($productData) {
    $product = new Product();
    $product->setOriginalId($productData['original_id']);
    $product->setQuantity(10);
    $product->setAvailableFrom(new \DateTime());
    $product->setTask($productData['task']);
    $product->setStatus(true);
    $product->setMinimum(1);
    $product->setSubtract(1);
    $product->setStockStatus(7);
    $product->setShipping(1);
    $product->setLengthClass(1);
    $product->setWeightClass(1);
    $product->setSortOrder(1);
    $product->setManufacturer(0);
    $product->setCategories($productData['task']->getCategories());
    $product->setName($productData['name']);
    $product->setUrl($productData['url']);
    $product->setSlug($productData['slug']);
    $product->setPrice($productData['price']);
    $product->setDescription($productData['description']);
    $product->setMetaDescription($productData['meta_description']);
    $product->setMetaKeywords($productData['meta_keywords']);

    $this->getEntityManager()->persist($product);
    $this->getEntityManager()->flush();

    return $product;
  }

  public function updateProduct($product, $productData) {
    $product->setName($productData['name']);
    $product->setUrl($productData['url']);
    $product->setSlug($productData['slug']);
    $product->setPrice($productData['price']);
    $product->setDescription($productData['description']);
    $product->setMetaDescription($productData['meta_description']);
    $product->setMetaKeywords($productData['meta_keywords']);

    $this->getEntityManager()->persist($product);
    $this->getEntityManager()->flush();
  }

  public function addProductImage($product, $image) {
  }
}