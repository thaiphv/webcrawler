<?php

namespace Mpf\CrawlerBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserAgentRepository extends EntityRepository {
  public function getUserAgentList($limit = 50) {
    $items = $this->createQueryBuilder('qb')
        ->select('u.name')
        ->from('MpfCrawlerBundle:UserAgent', 'u')
        ->where('u.status = 1')
        ->orderBy('u.mtime', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getArrayResult();

    $results = array();
    foreach ($items as $item) {
      $results[] = $item['name'];
    }

    return $results;
  }
}