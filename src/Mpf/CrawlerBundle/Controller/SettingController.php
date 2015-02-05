<?php

namespace Mpf\CrawlerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class SettingController extends Controller {

  /**
   * @Route("/setting", name="_setting_index")
   * @Template()
   */
  public function indexAction(Request $request) {
    $sm = $this->get('setting_manager');
    $form = $sm->getSettingForm();

    $form->handleRequest($request);
    if ($form->isValid()) {
      $formData = $form->getData();
      $sm->persistAllSettings($formData);
      $this->get('session')->getFlashBag()->add('notice', 'Your settings have been saved.');
    }

    return array('form' => $form->createView());
  }
} 