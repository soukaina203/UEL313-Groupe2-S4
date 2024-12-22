<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccountController extends AbstractController
{
 

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    //
    public function login(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher, 
        SessionInterface $session
    ): Response {
        $error = null;

        if ($request->isMethod('POST')) {

            $username = $request->request->get('username');
            $password = $request->request->get('password');

            if (!$username || !$password) {
                $error = 'Username and password are required.';
            } else {
                $user = $entityManager->getRepository(User::class)
                ->findOneBy(['username' => $username]);

                if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
                    $error = 'Invalid credentials.';
                } else {
                    $session->set('logged_user', $user);
                    return $this->redirectToRoute('admin_index');
                }
            }

            return $this->render('user/login.html.twig', [
                'error' => $error
            ]);

        }
            return $this->render('user/login.html.twig', [
                'error' => $error
            ]);

   
    }



    #[Route('/logout', name: 'logout', methods: ['GET'])]

        
    public function logout(TokenStorageInterface $tokenStorage,SessionInterface $session)
    {
        $tokenStorage->setToken(null);  
        $session->invalidate();

        return new RedirectResponse('/login');
    }
}
