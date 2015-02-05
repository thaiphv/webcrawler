<?php

namespace Mpf\CrawlerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Mpf\CrawlerBundle\Entity\Proxy;

class ProxyRepository extends EntityRepository {
  public function addOrUpdateProxy($ip, $port, $rank) {
    $item = $this->findOneBy(array('ip' => $ip, 'port' => $port));
    if (!$item) {
      $item = new Proxy();
      $item->setIp($ip);
      $item->setPort($port);
      $item->setStatus(1);
      $item->setRank($rank);

      $this->getEntityManager()->persist($item);
      $this->getEntityManager()->flush();
    }

    return $item;
  }

  public function getProxyList($limit = 100) {
    $items = $this->findBy(array('status' => 1), array('mtime' => 'DESC', 'rank' => 'DESC'), $limit);

    $results = array();
    foreach($items as $item) {
      $results[] = $item->getIp() . ':' . $item->getPort();
    }

    return $results;
  }
}