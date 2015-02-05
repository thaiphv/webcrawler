<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Mpf\CrawlerBundle\Repository\TaskRepository")
 * @ORM\Table(name="task")
 * @ORM\HasLifecycleCallbacks()
 */
class Task {
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=128, nullable=false)
   */
  protected $name;

  /**
   * @ORM\Column(type="boolean", nullable=false)
   */
  protected $status;

  /**
   * @ORM\Column(type="string", length=512)
   */
  protected $url;

  /**
   * @ORM\Column(type="text", name="direct_urls")
   */
  protected $directUrls;

  /**
   * @ORM\ManyToOne(targetEntity="Currency")
   * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
   **/
  protected $currency;

  /**
   * @ORM\ManyToMany(targetEntity="Category", inversedBy="tasks")
   * @ORM\JoinTable(name="task_category")
   **/
  protected $categories;

  /**
   * @ORM\ManyToOne(targetEntity="FileStorage")
   * @ORM\JoinColumn(name="file_storage_id", referencedColumnName="id")
   **/
  protected $fileStorage;

  /**
   * @ORM\Column(type="decimal", name="products_margin", scale=2)
   */
  protected $productsMargin;

  /**
   * @ORM\Column(type="decimal", name="fixed_products_margin", scale=2)
   */
  protected $fixedProductsMargin;

  /**
   * @ORM\ManyToOne(targetEntity="Parser")
   * @ORM\JoinColumn(name="parser_id", referencedColumnName="id")
   **/
  protected $parser;

  /**
   * @ORM\Column(type="boolean", name="crawl_all_images_flag")
   */
  protected $crawlAllImagesFlag;

  /**
   * @ORM\Column(type="boolean", name="overwrite_description_flag")
   */
  protected $overwriteDescriptionFlag;

  /**
   * @ORM\Column(type="boolean", name="seo_url_flag")
   */
  protected $seoUrlFlag;

  /**
   * @ORM\Column(type="text")
   */
  protected $comment;

  /**
   * @ORM\Column(type="datetime")
   */
  protected $ctime;

  /**
   * @ORM\Column(type="datetime")
   */
  protected $mtime;

  /**
   * @ORM\Column(type="datetime", name="last_executed_time")
   */
  protected $lastExecutedTime;

  public function __construct() {
    $this->categories = new ArrayCollection();
    $this->ctime = new \DateTime();
    $this->mtime = new \DateTime();
    $this->lastExecutedTime = new \DateTime('@0');
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
  public function getComment()
  {
    return $this->comment;
  }

  /**
   * @param mixed $comment
   */
  public function setComment($comment)
  {
    $this->comment = $comment;
  }

  /**
   * @return mixed
   */
  public function getCrawlAllImagesFlag()
  {
    return $this->crawlAllImagesFlag;
  }

  /**
   * @param mixed $crawlAllImagesFlag
   */
  public function setCrawlAllImagesFlag($crawlAllImagesFlag)
  {
    $this->crawlAllImagesFlag = $crawlAllImagesFlag;
  }

  /**
   * @return mixed
   */
  public function getCurrency()
  {
    return $this->currency;
  }

  /**
   * @param mixed $currency
   */
  public function setCurrency($currency)
  {
    $this->currency = $currency;
  }

  /**
   * @return mixed
   */
  public function getDirectUrls()
  {
    return $this->directUrls;
  }

  /**
   * @param mixed $directUrls
   */
  public function setDirectUrls($directUrls)
  {
    $this->directUrls = $directUrls;
  }

  /**
   * @return mixed
   */
  public function getFileStorage()
  {
    return $this->fileStorage;
  }

  /**
   * @param mixed $fileStorage
   */
  public function setFileStorage($fileStorage)
  {
    $this->fileStorage = $fileStorage;
  }

  /**
   * @return mixed
   */
  public function getFixedProductsMargin()
  {
    return $this->fixedProductsMargin;
  }

  /**
   * @param mixed $fixedProductsMargin
   */
  public function setFixedProductsMargin($fixedProductsMargin)
  {
    $this->fixedProductsMargin = $fixedProductsMargin;
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
  public function getOverwriteDescriptionFlag()
  {
    return $this->overwriteDescriptionFlag;
  }

  /**
   * @param mixed $overwriteDescriptionFlag
   */
  public function setOverwriteDescriptionFlag($overwriteDescriptionFlag)
  {
    $this->overwriteDescriptionFlag = $overwriteDescriptionFlag;
  }

  /**
   * @return Parser
   */
  public function getParser()
  {
    return $this->parser;
  }

  /**
   * @param mixed $parser
   */
  public function setParser(Parser $parser)
  {
    $this->parser = $parser;
  }

  /**
   * @return mixed
   */
  public function getProductsMargin()
  {
    return $this->productsMargin;
  }

  /**
   * @param mixed $productsMargin
   */
  public function setProductsMargin($productsMargin)
  {
    $this->productsMargin = $productsMargin;
  }

  /**
   * @return mixed
   */
  public function getSeoUrlFlag()
  {
    return $this->seoUrlFlag;
  }

  /**
   * @param mixed $seoUrlFlag
   */
  public function setSeoUrlFlag($seoUrlFlag)
  {
    $this->seoUrlFlag = $seoUrlFlag;
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
   * @ORM\PrePersist
   * @ORM\PreUpdate
   */
  public function setUpdateTime() {
    $this->setMtime(new \DateTime());
  }

  /**
   * @return mixed
   */
  public function getLastExecutedTime()
  {
    return $this->lastExecutedTime;
  }

  /**
   * @param mixed $lastExecutedTime
   */
  public function setLastExecutedTime($lastExecutedTime)
  {
    $this->lastExecutedTime = $lastExecutedTime;
  }
}