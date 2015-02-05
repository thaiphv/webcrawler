<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Mpf\CrawlerBundle\Repository\ImageRepository")
 * @ORM\Table(name="image")
 * @ORM\HasLifecycleCallbacks()
 */
class Image {
  const IMAGE_TYPE_MAIN = 0;
  const IMAGE_TYPE_SUB = 1;
  const IMAGE_TYPE_DESC = 2;

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
   * @ORM\Column(type="string", length=256, nullable=false)
   */
  protected $url;

  /**
   * @ORM\Column(type="string", name="url_hash", length=40, nullable=false, unique=true)
   */
  protected $urlHash;

  /**
   * @ORM\Column(type="string", length=256, nullable=false)
   */
  protected $path;

  /**
   * @ORM\Column(type="smallint", nullable=false)
   */
  protected $type;

  /**
   * @ORM\Column(type="string", name="alt_text", length=256, nullable=true)
   */
  protected $altText;

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
   * @ORM\ManyToMany(targetEntity="Product", mappedBy="images")
   **/
  protected $products;

  /**
   * @return mixed
   */
  public function getAltText()
  {
    return $this->altText;
  }

  /**
   * @param mixed $altText
   */
  public function setAltText($altText)
  {
    $this->altText = $altText;
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
  public function getPath()
  {
    return $this->path;
  }

  /**
   * @param mixed $path
   */
  public function setPath($path)
  {
    $this->path = $path;
  }

  /**
   * @return mixed
   */
  public function getProducts()
  {
    return $this->products;
  }

  /**
   * @param mixed $product
   */
  public function setProducts($products)
  {
    $this->products = $products;
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
  public function getType()
  {
    return $this->type;
  }

  /**
   * @param mixed $type
   */
  public function setType($type)
  {
    $this->type = $type;
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
    $this->urlHash = Product::hash($url);
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

  public function __construct() {
    $this->products = new ArrayCollection();
    $this->ctime = new \DateTime();
    $this->mtime = new \DateTime();
  }

  /**
   * @ORM\PrePersist
   * @ORM\PreUpdate
   */
  public function setUpdateTime() {
    $this->setMtime(new \DateTime());
  }
}