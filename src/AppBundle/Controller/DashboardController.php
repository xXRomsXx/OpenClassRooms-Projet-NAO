<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
  private $observationsService;
  private $usersService;

  public function __construct($observationsService, $usersService) {
    $this->observationsService = $observationsService;
    $this->usersService = $usersService;
  }

  public function dashboardAction(Request $request) {

    $countParticuliers = $this->usersService->countUsers('Particulier');  
    $countNaturalistes = $this->usersService->countUsers('Naturaliste'); 
    $countObservationsPublished = $this->observationsService->countObservations('Publiée'); 
    $countObservationsWaiting = $this->observationsService->countObservations('En attente');

    return $this->render(
      'default/dashboard.html.twig', 
      array(
        'countParticuliers' => $countParticuliers,
        'countNaturalistes' => $countNaturalistes,
        'countObservationsPublished' => $countObservationsPublished,
        'countObservationsWaiting' => $countObservationsWaiting
      )
    );

  }
}
