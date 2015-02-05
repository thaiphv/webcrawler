<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="currency")
 * @ORM\HasLifecycleCallbacks()
 */
class Currency {
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
   * @ORM\Column(type="string", length=32, nullable=false)
   */
  protected $code;

  /**
   * @ORM\Column(type="string", name="left_symbol", length=16)
   */
  protected $leftSymbol;

  /**
   * @ORM\Column(type="string", name="right_symbol", length=16)
   */
  protected $rightSymbol;

  /**
   * @ORM\Column(type="smallint", nullable=false)
   */
  protected $scale;

  /**
   * @ORM\Column(type="decimal", nullable=false)
   */
  protected $rate;

  /**
   * @ORM\Column(type="boolean", nullable=false)
   */
  protected $status;

  /**
   * @ORM\Column(type="datetime", nullable=false)
   */
  protected $ctime;

  /**
   * @ORM\Column(type="datetime", nullable=false)
   */
  protected $mtime;

  /**
   * @return mixed
   */
  public function getCode()
  {
    return $this->code;
  }

  /**
   * @param mixed $code
   */
  public function setCode($code)
  {
    $this->code = $code;
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
  public function getLeftSymbol()
  {
    return $this->leftSymbol;
  }

  /**
   * @param mixed $leftSymbol
   */
  public function setLeftSymbol($leftSymbol)
  {
    $this->leftSymbol = $leftSymbol;
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
  public function getScale()
  {
    return $this->scale;
  }

  /**
   * @param mixed $scale
   */
  public function setScale($scale)
  {
    $this->scale = $scale;
  }

  /**
   * @return mixed
   */
  public function getRate()
  {
    return $this->rate;
  }

  /**
   * @param mixed $rate
   */
  public function setRate($rate)
  {
    $this->rate = $rate;
  }

  /**
   * @return mixed
   */
  public function getRightSymbol()
  {
    return $this->rightSymbol;
  }

  /**
   * @param mixed $rightSymbol
   */
  public function setRightSymbol($rightSymbol)
  {
    $this->rightSymbol = $rightSymbol;
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
   * @ORM\PrePersist
   * @ORM\PreUpdate
   */
  public function setUpdateTime() {
    $this->setMtime(new \DateTime());
  }
}