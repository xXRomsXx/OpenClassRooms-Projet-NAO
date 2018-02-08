<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BlogBundle\Entity\News;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BlogController extends Controller
{
  public function newsListAction()
  {
  	$allNews = $this->getDoctrine()->getManager()->getRepository('BlogBundle:News')->findAllByDateAsc();
    return $this->render('default/news_list.html.twig', array('allNews' => $allNews));
  }

  public function newsAction($id)
  {
  	$news = $this->getDoctrine()->getManager()->getRepository('BlogBundle:News')->findOneBy(['id' => $id]);
    return $this->render('default/news.html.twig', array('news' => $news));
  }

  public function addNewsAction(Request $request)
  {

    $user = $this->getUser();

    $news = new News();

    // Création du formulaire
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $news);

    // Création des champs du formulaire
    $formBuilder
      ->add('title', TextType::class,
        array(
          'label' => 'Titre*',
          'attr' => array(
            'class' => 'form-control col-lg-10 m-auto',
            'placeholder' => 'Titre'
          )
        )
      )
      ->add('content', TextareaType::class,
        array(
          'label' => 'Contenu*',
          'attr' => array(
            'class' => 'form-control col-lg-10 m-auto',
            'placeholder' => 'Rédigez votre message',
          )
        )
      )
      ->add('submit', 
        SubmitType::class,
          array(
            'label' => 'Soumettre la news',
            'attr' => array(
              'class' => 'btn btn-info text-center col-lg-12'
            )
          )
      );

    // On génère le formulaire
    $form = $formBuilder->getForm();

    // Si le formulaire est soumis
    if ($request->isMethod('POST')) {

      // On fait le lien Requete <=> Formulaire, la variable $news contient les valeurs entrées dans le formulaire
      $form->handleRequest($request);

      // Si les données sont correctes
      if ($form->isValid()) { 

        // On recupère l'entity manager
        $em = $this->getDoctrine()->getManager();
        
        $picture = glob('Images/NewsPictures/News_temp.*');
        if ($picture) {
          $picture = new File($picture[0]);
          $extension = explode('.', $picture->getFileName())[1];
          $newFileName = 'Images/NewsPictures/' . $news->getDatePublished()->format('Y-m-d_H-i-s') . '_News.' . $extension;
          rename($picture->getPathName(), $newFileName);
          $news->setPicture($newFileName);
        }

        $em->persist($news);
        $em->flush();
        
        // On redirige vers la page suivante
        return $this->redirectToRoute('news', array('id' => $news->getId()));
      } else { // Si les données ne sont pas valides
        return $this->render('default/add_news.html.twig', array('form' => $form->createView()));
      }

    } else { // Si on arrive sur la page pour la première fois
      return $this->render('default/add_news.html.twig', array('form' => $form->createView()));
    }
  } // End of addNews()

  public function removeNewsAction($id)
  {
    $news = $this->getDoctrine()->getManager()->getRepository('BlogBundle:News')->findOneBy(['id' => $id]);
    if ($news) { // Si la news existe
      if ($news->getPicture() && file_exists($news->getPicture())) { // Si une photo est renseignée
        unlink($news->getPicture());
      }
      $this->getDoctrine()->getManager()->remove($news);
      $this->getDoctrine()->getManager()->flush();
      $this->addFlash('Success', 'L\'actualité a bien été supprimé !');
      return $this->redirectToRoute('list_news');
    } else { 
      $this->addFlash('Error', 'Cette actualité n\'existe pas !');
      return $this->redirectToRoute('list_news');
    }
  } // End of removeNews()


  public function ajaxDropzoneNewsAction(Request $request) {

    $em = $this->getDoctrine()->getManager();
    $pictureUploaded = $request->files->get('picture'); // Recuperation de la photo envoyée
    $extension = $pictureUploaded[0]->guessExtension();    
    $pictureFilename = 'Images/NewsPictures/News_temp.' . $extension; // On modifie son nom en attentant la soumission du formulaire "News_temp.extension"
    $pictureUploaded[0]->move( 
      $this->getParameter('news_pictures_directory'),
      $pictureFilename
    );
    return new Response();
  } // End of ajaxDropzoneNews()

} // End class
