<?php

namespace Innova\ForumMultimodalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Innova\ForumMultimodalBundle\Entity\Subject;

class ForumController extends Controller
{

  public function welcomeAction()
  {
    return $this->render('InnovaForumMultimodalBundle:Forum:reception.html.twig');
  }

  public function indexAction()
  {
    return $this->render('InnovaForumMultimodalBundle:Forum:index.html.twig');
  }

  public function addSubjectAction()
  {
        // On crée un objet Subject
    $Subject = new Subject();
    $user = $this->get('security.context')->getToken()->getUser();
    // print_r($user);
    // print_r($user->getId());
    // $userId = $user->getId();
    $Subject->setDate(new \Datetime());
    $Subject->setAuteur($user);

    $form = $this->createFormBuilder($Subject)
                ->add('sujet',       'text')
                ->add('Consigne', 'textarea')
                ->getForm();

    // On récupère la requête
    $request = $this->get('request');

    // On vérifie qu'elle est de type POST
    if ($request->getMethod() == 'POST') {
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
      $form->bind($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {
        // On l'enregistre notre objet $article dans la base de données
        $em = $this->getDoctrine()->getManager();
        $em->persist($Subject);
        $em->flush();

        // On redirige vers la page de visualisation de l'article nouvellement créé
        // return $this->redirect($this->generateUrl('innova_forum_multimodal_homepage', array('id' => $Subject->getId())));
        return $this->redirect($this->generateUrl('innova_forum_multimodal_homepage'));
      }
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
    $em2 = $this->getDoctrine()->getManager();
    $entities = $em2->getRepository('InnovaForumMultimodalBundle:Subject')->findAll();
    return $this->render('InnovaForumMultimodalBundle:Forum:index.html.twig', array(
      'form' => $form->createView(),'entities' => $entities,
    ));
  }
  public function deleteSubjectAction()
  {
    return $this->render('InnovaForumMultimodalBundle:Forum:index.html.twig');
  }
  public function updateSubjectAction()
  {
    return $this->render('InnovaForumMultimodalBundle:Forum:index.html.twig');
  }
}