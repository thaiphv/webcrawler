<?php

namespace Mpf\CrawlerBundle\Services;

use Mpf\CrawlerBundle\Entity\Setting;

class SettingManager {
  const KEY_STATUS = 'status';
  const KEY_MAX_LISTING_PAGES = 'max_listing_pages';
  const KEY_MAX_PRODUCT_PAGES = 'max_product_pages';
  const KEY_NOT_AVAILABLE_FLAG = 'not_available_flag';

  private $doctrine;
  private $formFactory;

  private $settingKeys = array(
    self::KEY_STATUS => 'boolean',
    self::KEY_MAX_LISTING_PAGES => 'integer',
    self::KEY_MAX_PRODUCT_PAGES => 'integer',
    self::KEY_NOT_AVAILABLE_FLAG => 'boolean'
  );

  public function __construct($doctrine, $formFactory) {
    $this->doctrine = $doctrine;
    $this->formFactory = $formFactory;
  }

  public function getSetting($key) {
    $repo = $this->doctrine->getManager()->getRepository('MpfCrawlerBundle:Setting');
    return $repo->find($key);
  }

  public function persistSetting($key, $value) {
    $em = $this->doctrine->getManager();
    if (!isset($this->settingKeys[$key])) {
      throw new \Exception('Invalid setting key: ' . $key);
    }
    $setting = $this->getSetting($key);
    if (!$setting) {
      $setting = new Setting();
      $setting->setKey($key);
      $setting->setType($this->settingKeys[$key]);
      $em->persist($setting);
    }
    $setting->setValue($value);
    $em->flush();

    return $setting;
  }

  public function persistAllSettings($settings) {
    foreach ($this->settingKeys as $key => $type) {
      if (isset($settings[$key])) {
        $this->persistSetting($key, $settings[$key]);
      }
    }
  }

  public function getAllSettings() {
    $settings = array();

    foreach ($this->settingKeys as $key => $type) {
      $setting = $this->getSetting($key);
      if ($setting) {
        $settings[$key] = $setting;
      } else {
        $settings[$key] = $this->persistSetting($key, null);
      }
    }

    return $settings;
  }

  public function getSettingForm() {
    $settings = $this->getAllSettings();
    $settingValues = array();
    foreach ($settings as $key => $setting) {
      $settingValues[$key] = $setting->getValue();
    }

    $formBuilder = $this->formFactory->createBuilder('form', $settingValues);
    foreach ($settings as $setting) {
      if ($setting->getType() == 'boolean') {
        $formBuilder->add($setting->getKey(), 'choice', array('choices' => array(1 => 'On', 0 => 'Off')));
      } else {
        $formBuilder->add($setting->getKey(), $setting->getType());
      }
    }

    return $formBuilder->getForm();
  }
}