<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminRouteListener
{
    private $router;
    private $requestStack;

    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();
        
        if (strpos($path, '/admin') === 0) {
            $session = $this->requestStack->getSession();

            // Check if session contains logged_user
            if (!$session || !$session->has('logged_user') || empty($session->get('logged_user'))) {
                // Redirect to login page if not logged in
                $response = new RedirectResponse($this->router->generate('login'));
                $event->setController(function () use ($response) {
                    return $response;
                });
                return;
            }

            // Retrieve logged_user and check role
            $loggedUser = $session->get('logged_user');
            if (!is_object($loggedUser) || !method_exists($loggedUser, 'getRole') || $loggedUser->getRole() !== 'admin') {
                // Redirect to login page if not admin
                $response = new RedirectResponse($this->router->generate('login'));
                $event->setController(function () use ($response) {
                    return $response;
                });
                return;
            }
        }
    }
}
