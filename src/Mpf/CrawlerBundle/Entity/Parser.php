<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="parser")
 */
class Parser {
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
   * @ORM\Column(type="string", name="class_name", length=256, nullable=false)
   */
  protected $className;

  /**
   * @ORM\Column(type="string", length=1024)
   */
  protected $config;

  /**
   * @return mixed
   */
  public function getClassName()
  {
    return $this->className;
  }

  /**
   * @param mixed $className
   */
  public function setClassName($className)
  {
    $this->className = $className;
  }

  /**
   * @return mixed
   */
  public function getConfig()
  {
    return json_decode($this->config);
  }

  /**
   * @param mixed $config
   */
  public function setConfig($config)
  {
    $this->config = json_encode($config);
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
}