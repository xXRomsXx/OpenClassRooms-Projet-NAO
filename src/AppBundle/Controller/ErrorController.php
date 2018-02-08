<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ErrorController extends Controller
{
  public function ErrorAction(Request $request) {
      return $this->render('default/error.html.twig');
  }

  public function accessDeniedAction(Request $request) {
    return $this->render('default/access_denied.html.twig');
  }
}