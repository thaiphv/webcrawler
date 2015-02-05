<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="category")
 * @ORM\HasLifecycleCallbacks()
 */
class Category {
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
   * @ORM\ManyToMany(targetEntity="Task", mappedBy="categories")
   **/
  protected $tasks;

  /**
   * @ORM\ManyToMany(targetEntity="Product", mappedBy="categories")
   **/
  protected $products;

  public function __construct() {
    $this->tasks = new ArrayCollection();
    $this->ctime = new \DateTime();
    $this->mtime = new \DateTime();
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
  public function getTasks()
  {
    return $this->tasks;
  }

  /**
   * @param mixed $tasks
   */
  public function setTasks($tasks)
  {
    $this->tasks = $tasks;
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
}