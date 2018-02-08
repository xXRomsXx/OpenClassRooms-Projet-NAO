<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{
  private $usersService;

  public function __construct($usersService) {
    $this->usersService = $usersService;
  }

  public function userAction($id) {
    $user = $this->usersService->findById($id);
    $activeUser = $this->getUser();
    if ($user == $activeUser) {
      return $this->render('@FOSUser/Profile/show.html.twig', array('user' => $user,));
    } else {
      return $this->render('default/user.html.twig', array("user" => $user));
    }
  }

  public function userListAction(Request $request) {
    $allUsers = $this->usersService->findAllByUsernameAsc();    
    return $this->render('default/users_list.html.twig', array("allUsers" => $allUsers));
  } // End of userListAction()

  public function promoteUserAction($id) {
    if ($this->usersService->promoteUser($id)) {
      $this->addFlash('Success', 'L\'utilisateur est désormais "Naturaliste" !');
      return $this->redirectToRoute('user_list');
    } else {
      $this->addFlash('Error', 'Cet utilisateur n\'existe pas !');
      return $this->redirectToRoute('user_list');
    }
  } 

  public function demoteUserAction($id) {
    if ($this->usersService->demoteUser($id)) {
      $this->addFlash('Success', 'L\'utilisateur est bien redevenu "Particulier" !');
      return $this->redirectToRoute('user_list');
    } else {
      $this->addFlash('Error', 'Cet utilisateur n\'existe pas !');
      return $this->redirectToRoute('user_list');
    }
  } 

  public function removeUserAction($id) {
    if ($this->usersService->removeUser($id)) {
      $this->addFlash('Success', 'L\'utilisateur a bien été supprimé !');
      return $this->redirectToRoute('user_list');
    } else {
      $this->addFlash('Error', 'Cet utilisateur n\'existe pas !');
      return $this->redirectToRoute('user_list');
    }
  } 




}
