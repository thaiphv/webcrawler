<?php

namespace Mpf\CrawlerBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository {
  public function getActiveTasks() {
    return $this->findBy(array('status' => 1), array('lastExecutedTime' => 'ASC'));
  }
} 