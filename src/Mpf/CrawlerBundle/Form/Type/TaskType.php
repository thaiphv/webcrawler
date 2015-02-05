<?php

namespace Mpf\CrawlerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
        ->add('name', 'text')
        ->add('status', 'choice', array('choices' => array('1' => 'On', '0' => 'Off')))
        ->add('url', 'url')
        ->add('directUrls', 'textarea', array('required' => false, 'attr' => array('rows' => 5)))
        ->add('currency', 'entity', array('class' => 'MpfCrawlerBundle:Currency', 'property' => 'code'))
        ->add('categories', 'entity', array('class' => 'MpfCrawlerBundle:Category', 'property' => 'name', 'multiple' => true, 'required' => true))
        ->add('fileStorage', 'entity', array('class' => 'MpfCrawlerBundle:FileStorage', 'property' => 'name'))
        ->add('productsMargin', 'number', array('required' => false))
        ->add('fixedProductsMargin', 'number', array('required' => false))
        ->add('parser', 'entity', array('class' => 'MpfCrawlerBundle:Parser', 'property' => 'name'))
        ->add('crawlAllImagesFlag', 'choice', array('choices' => array('1' => 'On', '0' => 'Off')))
        ->add('overwriteDescriptionFlag', 'choice', array('choices' => array('1' => 'On', '0' => 'Off')))
        ->add('seoUrlFlag', 'choice', array('choices' => array('1' => 'On', '0' => 'Off')))
        ->add('comment', 'textarea', array('required' => false))
    ;
  }

  public function getName() {
    return 'task';
  }
}