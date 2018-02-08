<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Observations;
use AppBundle\Entity\Pictures;
use AppBundle\Repository\BirdsRepository;
use AppBundle\Entity\Birds;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\File\File;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use AppBundle\FormType\BirdRaceType;


class ObservationsController extends Controller
{
  private $observationsService;
  private $picturesService;

  public function __construct($observationsService, $picturesService) {
    $this->observationsService = $observationsService;
    $this->picturesService = $picturesService;
  }

  public function observationListAction() {
    $allObservations = $this->observationsService->findAllByBirdNameAsc();    
    return $this->render('default/observations_list.html.twig', array("allObservations" => $allObservations));
  } // End of userListAction()

  public function observationAction($id) {
    $observation = $this->observationsService->findById($id);
    $activeUser = $this->getUser();
    return $this->render('default/observation.html.twig', array("observation" => $observation, 'activeUser' => $activeUser));
  } // End of observationAction()

  public function publishAction($id) {
    $observation = $this->observationsService->publishObservation($id);
    if ($observation) {
      $this->addFlash('Success', 'L\'observation a bien été publié !');
      return $this->redirectToRoute('observation', array("id" => $id));
    } else {
      $this->addFlash('Error', 'Cette observation n\'existe pas !');
      return $this->redirectToRoute('error_page');
    }
  } // End of publishAction()

  public function reportAction($id) {
    $observation = $this->observationsService->reportObservation($id);
    if ($observation) {
      $this->addFlash('Success', 'L\'observation a bien été signalé !');
      return $this->redirectToRoute('observation', array("id" => $id));
    } else {
      $this->addFlash('Error', 'Cette observation n\'existe pas !');
      return $this->redirectToRoute('error_page');
    }
  } // End of reportAction()

  public function removeAction($id) {
    if ($this->observationsService->removeObservation($id)) {
      $this->addFlash('Success', 'L\'observation a bien été supprimé !');
      return $this->redirectToRoute('observation_list');
    } else {
      $this->addFlash('Error', 'Cette observation n\'existe pas !');
      return $this->redirectToRoute('observation_list');
    }
  } // End of removeAction()

  public function addAction(Request $request) {

    $user = $this->getUser();

    $observation = new Observations();

    // Création du formulaire
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $observation);

    // Création des champs du formulaire
    $formBuilder
      ->add('birdName', TextType::class,
        array(
          'label' => 'Nom de l\'oiseau*',
          'attr' => array(
            'class' => 'form-control col-lg-10 m-auto',
            'placeholder' => 'Nom de l\'oiseau'
          )
        )
      )
      ->add('birdRace', EntityType::class, array(
          'class' => Birds::class,
          'query_builder' => function (BirdsRepository $birdsRepository) {
            return $birdsRepository->createQueryBuilder('b')->orderBy('b.race', 'ASC');
          },
          'choice_label' => 'race',
          'label' => 'Nom de l\'espèce*',
          'attr' => array(
            'class' => 'form-control col-lg-10 m-auto',
            'placeholder' => 'Nom de l\'espèce'  
          )
        )
      )
      ->add('publishedAt', DateType::class,
        array(
          'widget' => 'single_text',
          'html5'  => false,
          'attr'   => array(
            'class' => 'datepicker form-control col-lg-10 m-auto',
            'placeholder' => 'Date',
            'readonly' => true
          ),
          'format' => 'd/M/y',
          'label'  => 'Date de l\'observation*',
          'invalid_message' => 'Veuillez entrer une date valide',
          'constraints' => new NotBlank(
            array('message' => 'Le jour est obligatoire')
          )
        )
      )
      ->add('latitude', TextType::class,
        array(
          'label' => 'Latitude* (ex: 2.541254)',
          'attr' => array(
            'class' => 'form-control',
            'placeholder' => 'Latitude',
            'onblur' => 'checkGPS(this)'
          )
        )
      )
      ->add('longitude', TextType::class,
        array(
          'label' => 'Longitude* (ex: 45.254152)',
          'attr' => array(
            'class' => 'form-control',
            'placeholder' => 'Longitude',
            'onblur' => 'checkGPS(this)'
          )
        )
      )
      ->add('content', TextareaType::class,
        array(
          'label' => 'Commentaires (facultatif)',
          'required' => false,
          'attr' => array(
            'class' => 'form-control col-lg-10 m-auto',
            'placeholder' => 'Rédigez votre message',
          )
        )
      )
      ->add('submit', 
        SubmitType::class,
          array(
            'label' => 'Soumettre pour publication',
            'attr' => array(
              'class' => 'btn btn-info'
            )
          )
      );

    // On génère le formulaire
    $form = $formBuilder->getForm();

    // Si le formulaire est soumis
    if ($request->isMethod('POST')) {

      // On fait le lien Requete <=> Formulaire, la variable $observation contient les valeurs entrées dans le formulaire
      $form->handleRequest($request);

      // Si les données sont correctes
      if ($form->isValid()) { 

        // On recupère l'entity manager
        $em = $this->getDoctrine()->getManager();

        // Gestion de l'observation
        $observation->setAuthor($user->getUsername());
        $observation->setUser($user);

        $em->persist($observation);
        $em->flush();
        
        if ($user->getStatus() == "Naturaliste" || $user->getStatus() == "Administrateur" || $user->getStatus() == "Super Administrateur") 
        {
          $this->observationsService->publishObservation($observation->getId());
        }        

        // Ajout des photos
        $this->picturesService->linkToObservation($user->getUsernameCanonical(), $observation);
        
        // On redirige vers la page suivante
        return $this->redirectToRoute('observation', array('id' => $observation->getId()));
      } else { // Si les données ne sont pas valides
        return $this->render('default/add_observation.html.twig', array('form' => $form->createView()));
      }

    } else { // Si on arrive sur la page pour la première fois
      return $this->render('default/add_observation.html.twig', array('form' => $form->createView()));
    }
  } // End of add()

  public function modifyAction(Request $request, $id) {

    $user = $this->getUser();
    $observation = $this->observationsService->findByid($id);

    // Création du formulaire
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $observation);

    // Création des champs du formulaire
    $formBuilder
      ->add('birdName', TextType::class,
        array(
          'label' => 'Nom de l\'oiseau*',
          'attr' => array(
            'class' => 'form-control col-lg-10 m-auto',
            'placeholder' => 'Nom de l\'oiseau'
          )
        )
      )
      ->add('birdRace', EntityType::class, array(
          'class' => Birds::class,
          'query_builder' => function (BirdsRepository $birdsRepository) {
            return $birdsRepository->createQueryBuilder('b')->orderBy('b.race', 'ASC');
          },
          'choice_label' => 'race',
          'label' => 'Nom de l\'espèce*',
          'attr' => array(
            'class' => 'form-control col-lg-10 m-auto',
            'placeholder' => 'Nom de l\'espèce'  
          )
        )
      )
      ->add('publishedAt', DateType::class,
        array(
          'widget' => 'single_text',
          'html5'  => false,
          'attr'   => array(
            'class' => 'datepicker form-control col-lg-10 m-auto',
            'placeholder' => 'Date'
          ),
          'format' => 'd/M/y',
          'label'  => 'Date de l\'observation*',
          'invalid_message' => 'Veuillez entrer une date valide',
          'constraints' => new NotBlank(
            array('message' => 'Le jour est obligatoire')
          )
        )
      )
      ->add('latitude', TextType::class,
        array(
          'label' => 'Latitude*',
          'attr' => array(
            'class' => 'form-control',
            'placeholder' => 'Latitude',
            'onblur' => 'checkGPS(this)'
          )
        )
      )
      ->add('longitude', TextType::class,
        array(
          'label' => 'Longitude*',
          'attr' => array(
            'class' => 'form-control',
            'placeholder' => 'Longitude',
            'onblur' => 'checkGPS(this)'
          )
        )
      )
      ->add('content', TextareaType::class,
        array(
          'label' => 'Commentaires (facultatif)',
          'required' => false,
          'attr' => array(
            'class' => 'form-control col-lg-10 m-auto',
            'placeholder' => 'Rédigez votre message'
          )
        )
      )
      ->add('submit', 
        SubmitType::class,
          array(
            'label' => 'Soumettre pour publication',
            'attr' => array(
              'class' => 'btn btn-info'
            )
          )
      );

    // On génère le formulaire
    $form = $formBuilder->getForm();

    // Si le formulaire est soumis
    if ($request->isMethod('POST')) {

      // On fait le lien Requete <=> Formulaire, la variable $observation contient les valeurs entrées dans le formulaire
      $form->handleRequest($request);

      // Si les données sont correctes
      if ($form->isValid()) { 

        // On recupère l'entity manager
        $em = $this->getDoctrine()->getManager();

        // Gestion de l'observation
        $observation->setAuthor($user->getUsername());
        $observation->setUser($user);

        $em->persist($observation);
        $em->flush();

        // Ajout des photos
        $this->picturesService->linkToObservation($user->getUsernameCanonical(), $observation);

        // Flush
        $em->flush();

        // On redirige vers la page suivante
        return $this->redirectToRoute('observation', array('id' => $observation->getId()));
      } else { // Si les données ne sont pas valides
        $pictures = $observation->getPictures();
        return $this->render('default/modify_observation.html.twig', array('form' => $form->createView(), 'pictures' => $pictures));
      }
    } else { // Si on arrive sur la page pour la première fois
      $pictures = $observation->getPictures();
      return $this->render('default/modify_observation.html.twig', array('form' => $form->createView(), 'pictures' => $pictures));
    }
  } // End of modify()


  public function ajaxDropzoneAction(Request $request) {

    $em = $this->getDoctrine()->getManager();
    $user = $this->getUser();
    $picturesUploaded = $request->files->get('picture'); // Recuperation des photos envoyées
    $i = 1;

    // Supression d'eventuelles photos précédente sur le serveur
    $files = glob('Images/ObservationsPictures/Picture_' . $user->getUsernameCanonical() . '_*_temp.');
    foreach ($files as $file) {
      unlink($file);
    }

    // Supression d'eventuelles photos précédente dans la bdd
    $oldPictures = $this->picturesService->findAllTempByUsername($user->getUsernameCanonical());
    if ($oldPictures) {
      foreach ($oldPictures as $oldPicture) {
        $this->picturesService->removePicture($oldPicture->getName());
      }
    }

    foreach ($picturesUploaded as $picture) {
      $newPicture = new Pictures();
      $extension = $picture->guessExtension();
      $pictureFilename = 'Images/ObservationsPictures/Picture_' . $user->getUsernameCanonical() . '_' . $i . '_temp.' . $extension; // On modifie son nom en attentant la soumission du formulaire "Picture_temp_username_i.extension"
      $picture->move( // On la déplace dans le dossier src/Images/UsersPictures/
        $this->getParameter('observations_pictures_directory'),
        $pictureFilename
      );
      $newPicture->setFile(new File($pictureFilename));
      $newPicture->setName($pictureFilename);
      $em->persist($newPicture);
      $i++;
    }

    $em->flush();
    return new Response();
  } // End of ajaxDropzone()

  public function ajaxDropzoneModifyAction(Request $request) {

    $em = $this->getDoctrine()->getManager();
    $user = $this->getUser();

    $i = 1;

    // Supression d'eventuelles photos précédente sur le serveur
    $files = glob('Images/ObservationsPictures/Picture_' . $user->getUsernameCanonical() . '_*_tempbis.');
    foreach ($files as $file) {
      unlink($file);
    }

    // Supression d'eventuelles photos précédente dans la bdd
    $oldPictures = $this->picturesService->findAllTempBisByUsername($user->getUsernameCanonical());
    foreach ($oldPictures as $oldPicture) {
      $this->picturesService->removePicture($oldPicture->getName());
    }

    // Gestion des photos déja existante
    $existingPicturesName = $request->get('existingPictures'); // Recuperation de sphotos existantes

    if ($existingPicturesName) {
      $splitExistingPicturesName = explode(',', $existingPicturesName); // Recuepration des noms de chaque photo

      foreach ($splitExistingPicturesName as $existingPictureName) { // Pour chaque photo
        $existingPicture = $this->picturesService->findOneByName($existingPictureName); // On recupere cette photo dans la BDD
        $existingExtension = explode('.', $existingPictureName); // Recuperation de l'extension
        $newName = 'Images/ObservationsPictures/Picture_' . $user->getUsernameCanonical() . '_' . $i . '_temp.' . $existingExtension[1];
        rename($existingPictureName, $newName); // Renommage du fichier
        $existingPicture->setName($newName); // Renommage ds la BDD
        $existingPicture->setFile(new File($newName)); // Attrbution du fichier dans la BDD
        $em->persist($existingPicture); 
        $i++;
      }
    }

    // $debug = fopen('debug.txt', 'a');
    // fwrite($debug, var_export($picturesUploaded, true));
    // fclose($debug);
    // die();

    //Gestion des nouvelles photos
    $picturesUploaded = $request->files->get('picture'); // Recuperation des photos envoyées

    foreach ($picturesUploaded as $picture) {
      $newPicture = new Pictures();
      $extension = $picture->guessExtension();
      $pictureFilename = 'Images/ObservationsPictures/Picture_' . $user->getUsernameCanonical() . '_' . $i . '_tempbis.' . $extension; // On modifie son nom en attentant la soumission du formulaire "Picture_temp_username_i.extension"
      $picture->move( // On la déplace dans le dossier src/Images/UsersPictures/
        $this->getParameter('observations_pictures_directory'),
        $pictureFilename
      );
      $newPicture->setFile(new File($pictureFilename));
      $newPicture->setName($pictureFilename);
      $em->persist($newPicture);
      $i++;
    }

    $em->flush();
    return new Response();
  } // End of ajaxDropzoneModifyAction()

  public function ajaxRemovePictureAction(Request $request) {
    $pictureName = $request->get('pictureName');
    $this->picturesService->removePicture($pictureName);
    return new Response();
  } // End of ajaxRemovePicture()

} // End of class
