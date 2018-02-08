<?php

namespace AppBundle\Service;

class UsersService 
{

	private $usersRepository;
	private $fosUserManager;

	public function __construct($usersRepository, $fosUserManager) {
		$this->usersRepository = $usersRepository; // Recupération du repository Users via le service (injection de dépendance)
		$this->fosUserManager = $fosUserManager; // Récupération du service FosUserManager (injection de dépendance)
	}

  public function findAllByUsernameAsc() {
    return $this->usersRepository->findAllByUsernameAsc();
  } // End of findAllByUsernameAsc()

  public function findById($id) {
    $user = $this->fosUserManager->findUserBy(array('id' => $id));
    if ($user) { // Si le user existe
      return $user;
    } else { // Si l'id ne correspond à aucun user
      return false;
    }
  }

  public function promoteUser($id) { 
    $user = $this->fosUserManager->findUserBy(array('id' => $id));
    if ($user) { // Si le user existe
    	$user->addRole('ROLE_NATURALISTE');
    	$user->setStatus("Naturaliste");
    	$this->fosUserManager->updateUser($user);
    	return true;
    } else { // Si l'id ne correspond à aucun user
    	return false;
    }
  } // End of promoteUser()

  public function demoteUser($id) {
    $user = $this->fosUserManager->findUserBy(array('id' => $id));
    if ($user) { // Si le user existe
    	$user->removeRole('ROLE_NATURALISTE');
    	$user->setStatus("Particulier");
    	$this->fosUserManager->updateUser($user);
    	return true;
    } else { // Si l'id ne correspond à aucun user
    	return false;
    } 
  } // End of demoteUser()

  public function removeUser($id) {
    $user = $this->fosUserManager->findUserBy(array('id' => $id));
    if ($user) { // Si le user existe
    	$this->fosUserManager->deleteUser($user);
    	return true;
    } else { // Si l'id ne correspond à aucun user
    	return false;
    } 
  } // End of removeUser()

  public function countUsers($status) {
    return $this->usersRepository->countUsers($status);
  } // End of countUsers()

  public function incrementObservationsPublished($userId) {
    $user = $this->fosUserManager->findUserBy(array('id' => $userId));
    if ($user) {
      $user->setObservationsPublished($user->getObservationsPublished() + 1);
      $this->fosUserManager->updateUser($user);
      return true;
    } else {
      return false;
    }
  } // End of incrementObservationsPublished()

  public function decrementObservationsPublished($userId) {
    $user = $this->fosUserManager->findUserBy(array('id' => $userId));
    if ($user) {
      $user->setObservationsPublished($user->getObservationsPublished() - 1);
      $this->fosUserManager->updateUser($user);
      return true;
    } else {
      return false;
    }
  } // End of decrementObservationsPublished()
  
}
