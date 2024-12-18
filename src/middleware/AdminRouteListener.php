<?php
// src/EventListener/AdminRouteListener.php
namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdminRouteListener
{
    private $security;
    private $router;
    private $session;

    public function __construct(Security $security, UrlGeneratorInterface $router, SessionInterface $session)
    {
        $this->security = $security;
        $this->router = $router;
        $this->session = $session;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        // Check if the route is for the admin area
        if (preg_match('/^admin/', $request->get('_route'))) {
            // Check if the user is logged in either via session or Symfony's security system
            $user = $this->session->get('logged_user') ?: $this->security->getUser();

            // If there is no logged user in the session or security, redirect to login
            if (!$user) {
                $response = new RedirectResponse($this->router->generate('app_login'));
                $event->setResponse($response);
            }
        }
    }
}
?>
