<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\EmailContact;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactController extends Controller
{
  public function contactAction(Request $request) {

    $emailContact = new EmailContact();

    // Création du formulaire
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $emailContact);

    // Création des champs du formulaire
    $formBuilder
      ->add('name', TextType::class,
        array(
          'label' => 'Votre nom*',
          'label_attr' => array(
          	'class' => 'mb-0 mt-2'
          ),
          'attr' => array(
            'class' => 'form-control',
            'placeholder' => 'Votre nom'
          )
        )
      )
      ->add('email', EmailType::class,
        array(
          'label' => 'Votre email*',
          'label_attr' => array(
          	'class' => 'mb-0 mt-3'
          ),
          'attr' => array(
            'class' => 'form-control',
            'placeholder' => 'Votre email'
          )
        )
      )
      ->add('message', TextareaType::class,
        array(
          'label' => 'Votre message*',
          'label_attr' => array(
          	'class' => 'mb-0 mt-3'
          ),
          'attr' => array(
            'class' => 'form-control',
            'placeholder' => 'Votre message'
          )
        )
      )
      ->add('submit', SubmitType::class,
        array(
          'label' => 'Envoyer',
          'attr' => array(
          	'class' => 'mt-3 btn btn-info'
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
      	$message = (new \Swift_Message('Message du site NAO'))
		  		->setFrom($emailContact->getEmail())
		  		->setTo('thibault.fiacre@nao.fr')
		  		->setBody(
		  			$this->renderView(
		  				'default/email_contact.html.twig',
		  				array('emailContact' => $emailContact)
		  			)
		  		);
				$this->addFlash('Success', 'Votre message a bien été envoyé, nous vous en remercions et vous répondrons dans les meilleurs délais !');
        return $this->render('default/contact.html.twig', array('form' => $form->createView())); // On redirige vers la page
      } else { // Si les données ne sont pas valides
      	$this->addFlash('Error', 'Une erreur s\'est produite, veuillez reessayer !');
        return $this->render('default/contact.html.twig', array('form' => $form->createView()));
      }
    } else { // Si on arrive sur la page pour la première fois
      return $this->render('default/contact.html.twig', array('form' => $form->createView()));
    }
  } // End contactAction()
} // End class()
