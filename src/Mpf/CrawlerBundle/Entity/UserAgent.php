<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Mpf\CrawlerBundle\Repository\UserAgentRepository")
 * @ORM\Table(name="user_agent")
 * @ORM\HasLifecycleCallbacks()
 */
class UserAgent {
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=512, nullable=false)
   */
  protected $name;

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
   * @ORM\PrePersist
   * @ORM\PreUpdate
   */
  public function setUpdateTime() {
    $this->setMtime(new \DateTime());
  }
}