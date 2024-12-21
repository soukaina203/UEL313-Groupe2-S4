<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
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

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();
    
        // Log to check if the listener is triggered
        // You can use dump() or log your message here to see if it triggers
        dump('AdminRouteListener triggered');
        
        if (strpos($path, '/admin') === 0) {
            $session = $this->requestStack->getSession();
    
            // Check if session contains logged_user
            if (!$session || !$session->has('logged_user') || empty($session->get('logged_user'))) {
                // Redirect to login page if not logged in
                $response = new RedirectResponse($this->router->generate('login'));
                $event->setResponse($response);
                return;
            }
    
            // Retrieve logged_user and check role
            $loggedUser = $session->get('logged_user');
            if (!is_object($loggedUser) || !method_exists($loggedUser, 'getRole') || $loggedUser->getRole() !== 'admin') {
                // Redirect to login page if not admin
                $response = new RedirectResponse($this->router->generate('login'));
                $event->setResponse($response);
                return;
            }
        }
    }
    
    
}