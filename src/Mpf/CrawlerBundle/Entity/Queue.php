<?php

namespace Mpf\CrawlerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Mpf\CrawlerBundle\Repository\QueueRepository")
 * @ORM\Table(name="queue")
 */
class Queue {
  const ACTION_UPDATE_DESCRIPTION = 'update_description';
  const ACTION_DOWNLOAD_MAIN_IMAGE = 'download_main_image';
  const ACTION_DOWNLOAD_SUB_IMAGE = 'download_sub_image';
  const ACTION_DOWNLOAD_DESC_IMAGE = 'download_desc_image';

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=1024, nullable=false)
   */
  protected $url;

  /**
   * @ORM\ManyToOne(targetEntity="Task")
   * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
   **/
  protected $task;

  /**
   * @ORM\ManyToOne(targetEntity="Product")
   * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true)
   **/
  protected $product;

  /**
   * @ORM\Column(type="string", length=64, nullable=true)
   */
  protected $action;

  /**
   * @return mixed
   */
  public function getAction()
  {
    return $this->action;
  }

  /**
   * @param mixed $action
   */
  public function setAction($action)
  {
    $this->action = $action;
  }

  /**
   * @return mixed
   */
  public function getProduct()
  {
    return $this->product;
  }

  /**
   * @param mixed $product
   */
  public function setProduct($product)
  {
    $this->product = $product;
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
}