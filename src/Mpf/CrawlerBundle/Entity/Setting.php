<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="setting")
 */
class Setting {
  /**
   * @ORM\Id
   * @ORM\Column(type="string", name="setting_key", length=64)
   */
  protected $key;

  /**
   * @ORM\Column(type="string", length=512, nullable=false)
   */
  protected $value;

  /**
   * @ORM\Column(type="string", length=20, nullable=false)
   */
  protected $type;

  /**
   * @return mixed
   */
  public function getKey()
  {
    return $this->key;
  }

  /**
   * @param mixed $key
   */
  public function setKey($key)
  {
    $this->key = $key;
  }

  /**
   * @return mixed
   */
  public function getValue()
  {
    return $this->value ? json_decode($this->value) : null;
  }

  /**
   * @param mixed $value
   */
  public function setValue($value)
  {
    $this->value = json_encode($value);
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
}