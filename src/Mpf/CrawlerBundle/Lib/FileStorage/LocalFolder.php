<?php

namespace Mpf\CrawlerBundle\Lib\FileStorage;


use Mpf\CrawlerBundle\Entity\Image;

class LocalFolder extends  FileStorage {
  private $localPath;
  private $relativePath;

  public function __construct($localPath, $relativePath) {
    $this->localPath = $localPath;
    $this->relativePath = $relativePath;
  }

  public function storeFile($url, $content) {
    $urlInfo = parse_url($url);
    if (!$urlInfo['path']) {
      return null;
    }
    $filename = pathinfo($urlInfo['path'], PATHINFO_BASENAME);
    $hash = md5($url);
    $path = $this->localPath . '/' . $hash[0] . '/' . $hash[1] . '/' . $hash[2] . '/' . $hash[3];
    $relativePath = $this->relativePath . '/' . $hash[0] . '/' . $hash[1] . '/' . $hash[2] . '/' . $hash[3];
    if (!@mkdir($path, 0775, true)) {
      return null;
    }
    @file_put_contents($path . '/' . $filename, $content);

    $imageEntity = new Image();
    $imageEntity->setName($filename);
    $imageEntity->setStatus(true);
    $imageEntity->setPath($relativePath . '/' . $filename);

    return $imageEntity;
  }
} 