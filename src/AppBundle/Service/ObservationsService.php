<?php

namespace AppBundle\Service;

class ObservationsService 
{

	private $observationsRepository;
  private $em;
  private $usersService;

	public function __construct($observationsRepository, $entityManager, $usersService) {
		$this->observationsRepository = $observationsRepository; // Recupération du repository Observations via le service (injection de dépendance)
    $this->em = $entityManager; // Récupération du service de l'entity manager
    $this->usersService = $usersService; // Récupération du service de gestion des users
	}

  public function findAllByBirdNameAsc() 
  {
    return $this->observationsRepository->findAllByBirdNameAsc();
  } // End of findAllByBirdNameAsc()

  public function findById($id) 
  {
    return $this->observationsRepository->findOneById($id);
  } // End of findOneById()

  public function publishObservation($id) {
    $observation = $this->findById($id);
    if ($observation) { // Si l'observation existe
      $observation->setState("Publiée");
      $this->usersService->incrementObservationsPublished($observation->getUser()->getId());
      $this->em->persist($observation);
      $this->em->flush();
      return $observation;
    } else { // Si l'id ne correspond à aucune observation
      return false;
    } 
  } // End of publishObservation()

  public function removeObservation($id) {
    $observation = $this->findById($id);
    if ($observation) { // Si l'observation existe
      if ($observation->getState() == "Publiée") {
        $this->usersService->decrementObservationsPublished($observation->getUser()->getId());
      }
    	$observation->setState("Supprimée");
      $this->em->persist($observation);
      $this->em->flush();
      return $observation;
    } else { // Si l'id ne correspond à aucune observation
    	return false;
    } 
  } // End of removeObservation()

  public function reportObservation($id) {
    $observation = $this->findById($id);
    if ($observation) { // Si l'observation existe
      $observation->setReported($observation->getReported() + 1);
      $this->em->persist($observation);
      $this->em->flush();
      return $observation;
    } else { // Si l'id ne correspond à aucune observation
      return false;
    } 
  } // End of reportObservation()

  public function countObservations($state) {
    return $this->observationsRepository->countObservations($state);
  } // End of countUsers()

  
}
