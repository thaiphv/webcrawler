<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Mpf\CrawlerBundle\Repository\ProductRepository")
 * @ORM\Table(name="product")
 * @ORM\HasLifecycleCallbacks()
 */
class Product {
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=32, unique=true, nullable=false, name="original_id")
   */
  protected $originalId;

  /**
   * @ORM\Column(type="string", length=512, nullable=false)
   */
  protected $name;

  /**
   * @ORM\Column(type="text")
   */
  protected $description;

  /**
   * @ORM\Column(type="string", name="meta_description", length=512)
   */
  protected $metaDescription;

  /**
   * @ORM\Column(type="string", name="meta_keywords", length=512)
   */
  protected $metaKeywords;

  /**
   * @ORM\Column(type="string", length=512)
   */
  protected $slug;

  /**
   * @ORM\Column(type="string", length=512)
   */
  protected $url;

  /**
   * @ORM\Column(type="string", name="url_hash", length=64, unique=true)
   */
  protected $urlHash;

  /**
   * @ORM\Column(type="string", length=64, nullable=true)
   */
  protected $sku;

  /**
   * @ORM\Column(type="string", length=64, nullable=true)
   */
  protected $upc;

  /**
   * @ORM\Column(type="string", length=64, nullable=true)
   */
  protected $ean;

  /**
   * @ORM\Column(type="string", length=64, nullable=true)
   */
  protected $jan;

  /**
   * @ORM\Column(type="string", length=64, nullable=true)
   */
  protected $isbn;

  /**
   * @ORM\Column(type="string", length=64, nullable=true)
   */
  protected $mpn;

  /**
   * @ORM\Column(type="string", length=128, nullable=true)
   */
  protected $location;

  /**
   * @ORM\Column(type="integer")
   */
  protected $quantity;

  /**
   * @ORM\Column(type="integer", name="stock_status_id", nullable=true)
   */
  protected $stockStatus;

  /**
   * @ORM\Column(type="integer", name="manufacturer_id", nullable=true)
   */
  protected $manufacturer;

  /**
   * @ORM\Column(type="smallint", nullable=true)
   */
  protected $shipping;

  /**
   * @ORM\Column(type="decimal", scale=2)
   */
  protected $price;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  protected $points;

  /**
   * @ORM\Column(type="integer", name="tax_class_id", nullable=true)
   */
  protected $taxClass;

  /**
   * @ORM\Column(type="datetime", name="available_from")
   */
  protected $availableFrom;

  /**
   * @ORM\Column(type="decimal", scale=2, nullable=true)
   */
  protected $weight;

  /**
   * @ORM\Column(type="integer", name="weight_class_id", nullable=true)
   */
  protected $weightClass;

  /**
   * @ORM\Column(type="decimal", scale=2, nullable=true)
   */
  protected $length;

  /**
   * @ORM\Column(type="decimal", scale=2, nullable=true)
   */
  protected $width;

  /**
   * @ORM\Column(type="decimal", scale=2, nullable=true)
   */
  protected $height;

  /**
   * @ORM\Column(type="integer", name="length_class_id", nullable=true)
   */
  protected $lengthClass;

  /**
   * @ORM\Column(type="smallint", nullable=true)
   */
  protected $subtract;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  protected $minimum;

  /**
   * @ORM\Column(type="integer", name="sort_order", nullable=true)
   */
  protected $sortOrder;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  protected $viewed;

  /**
   * @ORM\Column(type="boolean")
   */
  protected $status;

  /**
   * @ORM\Column(type="datetime")
   */
  protected $ctime;

  /**
   * @ORM\Column(type="datetime")
   */
  protected $mtime;

  /**
   * @ORM\ManyToMany(targetEntity="Image", inversedBy="products")
   * @ORM\JoinTable(name="product_image")
   **/
  protected $images;

  /**
   * @ORM\ManyToOne(targetEntity="Store")
   * @ORM\JoinColumn(name="store_id", referencedColumnName="id")
   */
  protected $store;

  /**
   * @ORM\ManyToMany(targetEntity="Category", inversedBy="products")
   * @ORM\JoinTable(name="product_category")
   **/
  protected $categories;

  /**
   * @ORM\ManyToOne(targetEntity="Task")
   * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
   */
  protected $task;

  /**
   * @ORM\OneToOne(targetEntity="Product")
   * @ORM\JoinColumn(name="child_product_id", referencedColumnName="id", nullable=true)
   **/
  protected $childProduct;

  public function __construct() {
    $this->images = new ArrayCollection();
    $this->mtime = new \DateTime();
    $this->ctime = new \DateTime();
  }

  /**
   * @return mixed
   */
  public function getAvailableFrom()
  {
    return $this->availableFrom;
  }

  /**
   * @param mixed $availableFrom
   */
  public function setAvailableFrom($availableFrom)
  {
    $this->availableFrom = $availableFrom;
  }

  /**
   * @return mixed
   */
  public function getCategories()
  {
    return $this->categories;
  }

  /**
   * @param mixed $categories
   */
  public function setCategories($categories)
  {
    $this->categories = $categories;
  }

  /**
   * @return mixed
   */
  public function getCtime()
  {
    return $this->ctime;
  }

  /**
   * @param mixed $ctime
   */
  public function setCtime($ctime)
  {
    $this->ctime = $ctime;
  }

  /**
   * @return mixed
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * @param mixed $description
   */
  public function setDescription($description)
  {
    $this->description = $description;
  }

  /**
   * @return mixed
   */
  public function getEan()
  {
    return $this->ean;
  }

  /**
   * @param mixed $ean
   */
  public function setEan($ean)
  {
    $this->ean = $ean;
  }

  /**
   * @return mixed
   */
  public function getHeight()
  {
    return $this->height;
  }

  /**
   * @param mixed $height
   */
  public function setHeight($height)
  {
    $this->height = $height;
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getImages()
  {
    return $this->images;
  }

  /**
   * @param mixed $images
   */
  public function setImages($images)
  {
    $this->images = $images;
  }

  /**
   * @param Image $image
   */
  public function addImage($image) {
    $this->images[] = $image;
  }

  /**
   * @return mixed
   */
  public function getIsbn()
  {
    return $this->isbn;
  }

  /**
   * @param mixed $isbn
   */
  public function setIsbn($isbn)
  {
    $this->isbn = $isbn;
  }

  /**
   * @return mixed
   */
  public function getJan()
  {
    return $this->jan;
  }

  /**
   * @param mixed $jan
   */
  public function setJan($jan)
  {
    $this->jan = $jan;
  }

  /**
   * @return mixed
   */
  public function getLength()
  {
    return $this->length;
  }

  /**
   * @param mixed $length
   */
  public function setLength($length)
  {
    $this->length = $length;
  }

  /**
   * @return mixed
   */
  public function getLengthClass()
  {
    return $this->lengthClass;
  }

  /**
   * @param mixed $lengthClass
   */
  public function setLengthClass($lengthClass)
  {
    $this->lengthClass = $lengthClass;
  }

  /**
   * @return mixed
   */
  public function getLocation()
  {
    return $this->location;
  }

  /**
   * @param mixed $location
   */
  public function setLocation($location)
  {
    $this->location = $location;
  }

  /**
   * @return mixed
   */
  public function getManufacturer()
  {
    return $this->manufacturer;
  }

  /**
   * @param mixed $manufacturer
   */
  public function setManufacturer($manufacturer)
  {
    $this->manufacturer = $manufacturer;
  }

  /**
   * @return mixed
   */
  public function getMetaDescription()
  {
    return $this->metaDescription;
  }

  /**
   * @param mixed $metaDescription
   */
  public function setMetaDescription($metaDescription)
  {
    $this->metaDescription = $metaDescription;
  }

  /**
   * @return mixed
   */
  public function getMetaKeywords()
  {
    return $this->metaKeywords;
  }

  /**
   * @param mixed $metaKeywords
   */
  public function setMetaKeywords($metaKeywords)
  {
    $this->metaKeywords = $metaKeywords;
  }

  /**
   * @return mixed
   */
  public function getMinimum()
  {
    return $this->minimum;
  }

  /**
   * @param mixed $minimum
   */
  public function setMinimum($minimum)
  {
    $this->minimum = $minimum;
  }

  /**
   * @return mixed
   */
  public function getMpn()
  {
    return $this->mpn;
  }

  /**
   * @param mixed $mpn
   */
  public function setMpn($mpn)
  {
    $this->mpn = $mpn;
  }

  /**
   * @return mixed
   */
  public function getMtime()
  {
    return $this->mtime;
  }

  /**
   * @param mixed $mtime
   */
  public function setMtime($mtime)
  {
    $this->mtime = $mtime;
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getPoints()
  {
    return $this->points;
  }

  /**
   * @param mixed $points
   */
  public function setPoints($points)
  {
    $this->points = $points;
  }

  /**
   * @return mixed
   */
  public function getPrice()
  {
    return $this->price;
  }

  /**
   * @param mixed $price
   */
  public function setPrice($price)
  {
    $this->price = $price;
  }

  /**
   * @return mixed
   */
  public function getQuantity()
  {
    return $this->quantity;
  }

  /**
   * @param mixed $quantity
   */
  public function setQuantity($quantity)
  {
    $this->quantity = $quantity;
  }

  /**
   * @return mixed
   */
  public function getShipping()
  {
    return $this->shipping;
  }

  /**
   * @param mixed $shipping
   */
  public function setShipping($shipping)
  {
    $this->shipping = $shipping;
  }

  /**
   * @return mixed
   */
  public function getSku()
  {
    return $this->sku;
  }

  /**
   * @param mixed $sku
   */
  public function setSku($sku)
  {
    $this->sku = $sku;
  }

  /**
   * @return mixed
   */
  public function getSlug()
  {
    return $this->slug;
  }

  /**
   * @param mixed $slug
   */
  public function setSlug($slug)
  {
    $this->slug = $slug;
  }

  /**
   * @return mixed
   */
  public function getSortOrder()
  {
    return $this->sortOrder;
  }

  /**
   * @param mixed $sortOrder
   */
  public function setSortOrder($sortOrder)
  {
    $this->sortOrder = $sortOrder;
  }

  /**
   * @return mixed
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * @param mixed $status
   */
  public function setStatus($status)
  {
    $this->status = $status;
  }

  /**
   * @return mixed
   */
  public function getStockStatus()
  {
    return $this->stockStatus;
  }

  /**
   * @param mixed $stockStatus
   */
  public function setStockStatus($stockStatus)
  {
    $this->stockStatus = $stockStatus;
  }

  /**
   * @return mixed
   */
  public function getStore()
  {
    return $this->store;
  }

  /**
   * @param mixed $store
   */
  public function setStore($store)
  {
    $this->store = $store;
  }

  /**
   * @return mixed
   */
  public function getSubtract()
  {
    return $this->subtract;
  }

  /**
   * @param mixed $subtract
   */
  public function setSubtract($subtract)
  {
    $this->subtract = $subtract;
  }

  /**
   * @return mixed
   */
  public function getTask()
  {
    return $this->task;
  }

  /**
   * @param mixed $task
   */
  public function setTask($task)
  {
    $this->task = $task;
  }

  /**
   * @return mixed
   */
  public function getTaxClass()
  {
    return $this->taxClass;
  }

  /**
   * @param mixed $taxClass
   */
  public function setTaxClass($taxClass)
  {
    $this->taxClass = $taxClass;
  }

  /**
   * @return mixed
   */
  public function getUpc()
  {
    return $this->upc;
  }

  /**
   * @param mixed $upc
   */
  public function setUpc($upc)
  {
    $this->upc = $upc;
  }

  /**
   * @return mixed
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * @param mixed $url
   */
  public function setUrl($url)
  {
    $this->url = $url;
    $this->setUrlHash(self::hash($url));
  }

  /**
   * @return mixed
   */
  public function getUrlHash()
  {
    return $this->urlHash;
  }

  /**
   * @param mixed $urlHash
   */
  public function setUrlHash($urlHash)
  {
    $this->urlHash = $urlHash;
  }

  /**
   * @return mixed
   */
  public function getViewed()
  {
    return $this->viewed;
  }

  /**
   * @param mixed $viewed
   */
  public function setViewed($viewed)
  {
    $this->viewed = $viewed;
  }

  /**
   * @return mixed
   */
  public function getWeight()
  {
    return $this->weight;
  }

  /**
   * @param mixed $weight
   */
  public function setWeight($weight)
  {
    $this->weight = $weight;
  }

  /**
   * @return mixed
   */
  public function getWeightClass()
  {
    return $this->weightClass;
  }

  /**
   * @param mixed $weightClass
   */
  public function setWeightClass($weightClass)
  {
    $this->weightClass = $weightClass;
  }

  /**
   * @return mixed
   */
  public function getWidth()
  {
    return $this->width;
  }

  /**
   * @param mixed $width
   */
  public function setWidth($width)
  {
    $this->width = $width;
  }

  /**
   * @ORM\PrePersist
   * @ORM\PreUpdate
   */
  public function setUpdateTime() {
    $this->setMtime(new \DateTime());
  }

  /**
   * @return mixed
   */
  public function getChildProduct()
  {
    return $this->childProduct;
  }

  /**
   * @param mixed $childProduct
   */
  public function setChildProduct($childProduct)
  {
    $this->childProduct = $childProduct;
  }

  /**
   * @return mixed
   */
  public function getOriginalId()
  {
    return $this->originalId;
  }

  /**
   * @param mixed $originalId
   */
  public function setOriginalId($originalId)
  {
    $this->originalId = $originalId;
  }

  static public function hash($string) {
    return sha1($string);
  }
}