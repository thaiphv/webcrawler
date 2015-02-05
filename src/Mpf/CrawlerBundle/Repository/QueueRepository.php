<?php

namespace Mpf\CrawlerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Mpf\CrawlerBundle\Entity\Queue;

class QueueRepository extends EntityRepository {
  public function queueJob($url, $task, $product=null, $action=null) {
    $item = new Queue();
    $item->setUrl($url);
    $item->setTask($task);
    if ($product) {
      $item->setProduct($product);
    }
    if ($action) {
      $item->setAction($action);
    }

    $this->getEntityManager()->persist($item);
    $this->getEntityManager()->flush();

    return $item;
  }

  public function getJobs($parser, $limit=10) {
    $query = $this->createQueryBuilder('q')
        ->innerJoin('q.task', 't')
        ->where('t.parser = :parser')
        ->setParameter('parser', $parser)
        ->setMaxResults($limit)
        ->getQuery()
    ;
    return $query->getResult();
  }

  public function removeJob($job) {
    $this->getEntityManager()->remove($job);
    $this->getEntityManager()->flush();
  }
}