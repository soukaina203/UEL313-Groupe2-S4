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
    #[Route('/', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        SessionInterface $session // Add the session interface

    ): Response {
        $error = null; // Initialize error variable
    
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');
    
            // Validate input
            if (!$username || !$password) {
                $error = 'Username and password are required.';
            } else {
                // Find the user
                $user = $entityManager->getRepository(User::class)->
                findOneBy(['username' => $username]);
    
                // Validate credentials
                if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
                    $error = 'Invalid credentials.';
                } else {
                    // Successful login: redirect to admin page
                    $session->set('logged_user', $user);

                    return $this->redirectToRoute('admin_index');
                    
                }
            }
        }
    
        // Render the login form (with or without error)
        return $this->render('user/login.html.twig', [
            'error' => $error, // Pass the error variable to Twig
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]

    public function logout(TokenStorageInterface $tokenStorage)
    {
        // Manually clear the security token
        $tokenStorage->setToken(null);  // Invalidate the authentication token

        // You can redirect to the login page or any other page after logout
        return new RedirectResponse('/login');
    }
}
