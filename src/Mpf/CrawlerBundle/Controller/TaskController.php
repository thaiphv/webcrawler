<?php

namespace Mpf\CrawlerBundle\Controller;

use Mpf\CrawlerBundle\Entity\Task;
use Mpf\CrawlerBundle\Form\Type\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller {

  /**
   * @Route("/task", name="_task_index")
   * @Template()
   */
  public function indexAction() {
    $tasks = $this->getDoctrine()->getManager()->getRepository('MpfCrawlerBundle:Task')->findAll();

    return array('tasks' => $tasks);
  }

  /**
   * @Route("/task/new", name="_task_new")
   * @Template()
   */
  public function newAction(Request $request) {
    $task = new Task();
    $form = $this->createForm(new TaskType(), $task);

    $form->handleRequest($request);
    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($task);
      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'New crawler task has been created successfully.');
    }

    return array('form' => $form->createView());
  }

  /**
   * @Route("/task/edit/{taskId}", name="_task_edit", requirements={"taskId" = "\d+"})
   * @Template()
   */
  public function editAction(Request $request, $taskId) {
    $task = $this->getDoctrine()->getRepository('MpfCrawlerBundle:Task')->findOneBy(array('id' => $taskId));
    if (!$task) {
      $this->get('session')->getFlashBag()->add('error', 'Invalid request crawl task ID.');
      return $this->redirect($this->generateUrl('_task_index'));
    }

    $form = $this->createForm(new TaskType(), $task);

    $form->handleRequest($request);
    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($task);
      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Crawler task has been updated successfully.');
    }

    return array('form' => $form->createView(), 'taskId' => $taskId);
  }

  /**
   * @Route("/task/disable/{taskId}", name="_task_disable", requirements={"taskId" = "\d+"})
   * @Template()
   */
  public function disableAction($taskId) {
    $task = $this->getDoctrine()->getRepository('MpfCrawlerBundle:Task')->findOneBy(array('id' => $taskId));
    if (!$task) {
      $this->get('session')->getFlashBag()->add('error', 'Invalid request crawl task ID.');
      return $this->redirect($this->generateUrl('_task_index'));
    }

    $task->setStatus(0);
    $em = $this->getDoctrine()->getManager();
    $em->persist($task);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Crawler task has been disabled.');
    return $this->redirect($this->generateUrl('_task_index'));
  }

  /**
   * @Route("/task/clone/{taskId}", name="_task_clone", requirements={"taskId" = "\d+"})
   * @Template()
   */
  public function cloneAction($taskId) {
    $task = $this->getDoctrine()->getRepository('MpfCrawlerBundle:Task')->findOneBy(array('id' => $taskId));
    if (!$task) {
      $this->get('session')->getFlashBag()->add('error', 'Invalid request crawl task ID.');
      return $this->redirect($this->generateUrl('_task_index'));
    }

    /** @var Task $task */
    $newTask = new Task();
    $newTask->setName($task->getName());
    $newTask->setStatus($task->getStatus());
    $newTask->setUrl($task->getUrl());
    $newTask->setDirectUrls($task->getDirectUrls());
    $newTask->setCurrency($task->getCurrency());
    $newTask->setCategories($task->getCategories());
    $newTask->setFileStorage($task->getFileStorage());
    $newTask->setProductsMargin($task->getProductsMargin());
    $newTask->setFixedProductsMargin($task->getFixedProductsMargin());
    $newTask->setParser($task->getParser());
    $newTask->setCrawlAllImagesFlag($task->getCrawlAllImagesFlag());
    $newTask->setOverwriteDescriptionFlag($task->getOverwriteDescriptionFlag());
    $newTask->setSeoUrlFlag($task->getSeoUrlFlag());
    $newTask->setComment($task->getComment());

    $em = $this->getDoctrine()->getManager();
    $em->persist($newTask);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'New crawler task has been cloned.');
    return $this->redirect($this->generateUrl('_task_edit', array('taskId' => $newTask->getId())));
  }
} 