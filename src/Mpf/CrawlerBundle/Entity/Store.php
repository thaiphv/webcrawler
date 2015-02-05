<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="store")
 */
class Store {
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=64, nullable=false)
   */
  protected $name;

  /**
   * @ORM\Column(type="string", length=256, nullable=false)
   */
  protected $url;

  /**
   * @ORM\Column(type="string", name="url_hash", length=64, unique=true, nullable=false)
   */
  protected $urlHash;

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

  static public function hash($string) {
    return sha1($string);
  }
}