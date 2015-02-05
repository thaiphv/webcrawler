<?php

namespace Mpf\CrawlerBundle\Lib\FileStorage;


abstract class FileStorage {
  abstract public function storeFile($url, $content);
}