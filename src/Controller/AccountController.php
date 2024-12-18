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
        return $this->render('user/login.html.twig',['error'=>null]);
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
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
                $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

                if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
                    $error = 'Invalid credentials.';
                } else {
                    $session->set('logged_user', $user);
                    return $this->redirectToRoute('admin_index');
                }
            }

            // Render the login template again with the error
            return $this->render('user/login.html.twig', [
                'error' => $error
            ]);
        }

        // For GET request
        return $this->render('user/login.html.twig', [
            'error' => null
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
