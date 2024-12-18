<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{

    #[Route('/admin', name: 'admin_index')]
    public function index(SessionInterface $session,UserRepository $userRepository): Response
    {
        $connectedUser = $session->get('logged_user');
        $users =$userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'user' => $connectedUser,
            'users'=>$users
        ]);
    }
    
    
}
