<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Mpf\CrawlerBundle\Repository\ProxyRepository")
 * @ORM\Table(name="proxy")
 * @ORM\HasLifecycleCallbacks()
 */
class Proxy {
  /**
   * @ORM\Id
   * @ORM\Column(type="string", length=128)
   */
  protected $ip;

  /**
   * @ORM\Id
   * @ORM\Column(type="string", length=16)
   */
  protected $port;


  /**
   * @ORM\Column(type="smallint", nullable=false, options={"default":1})
   */
  protected $rank;

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

  public function __construct() {
    $this->ctime = new \DateTime();
    $this->mtime = new \DateTime();
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
  public function getIp()
  {
    return $this->ip;
  }

  /**
   * @param mixed $ip
   */
  public function setIp($ip)
  {
    $this->ip = $ip;
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
  public function getPort()
  {
    return $this->port;
  }

  /**
   * @param mixed $port
   */
  public function setPort($port)
  {
    $this->port = $port;
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
  public function getRank()
  {
    return $this->rank;
  }

  /**
   * @param mixed $rank
   */
  public function setRank($rank)
  {
    $this->rank = $rank;
  }

  /**
   * @ORM\PrePersist
   * @ORM\PreUpdate
   */
  public function setUpdateTime() {
    $this->setMtime(new \DateTime());
  }
}