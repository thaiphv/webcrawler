<?php

namespace Mpf\CrawlerBundle\Services;

use Mpf\CrawlerBundle\Entity\Log;

class Logger {
  private $doctrine;

  public function __construct($doctrine) {
    $this->doctrine = $doctrine;
  }

  public function log($message) {
    $entity = new Log();
    $entity->setMessage($message);

    $em = $this->doctrine->getManager();
    $em->persist($entity);
    $em->flush();
  }
}