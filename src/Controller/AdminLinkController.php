<?php

namespace App\Controller;

use App\Repository\LinkRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminLinkController extends AbstractController
{

    #[Route('/admin/link', name: 'adminLink_index')]
    public function indexLink(SessionInterface $session,LinkRepository $linkRepository): Response
    {
        $connectedUser = $session->get('logged_user');
        $links =$linkRepository->findAll();

        return $this->render('admin/indexLink.html.twig', [
            'user' => $connectedUser,
            'links' => $links
        ]);
    }
    
    
}
