<?php

// namespace App\EventListener;

// namespace App\EventListener;

// use Symfony\Component\HttpFoundation\RedirectResponse;
// use Symfony\Component\HttpKernel\Event\RequestEvent;
// use Symfony\Component\Security\Core\Exception\AccessDeniedException;
// use Symfony\Component\Routing\RouterInterface;
// use Symfony\Component\HttpKernel\Event\ExceptionEvent;



// class AccessDeniedListener
// {
//     private $router;

//     public function __construct(RouterInterface $router)
//     {
//         $this->router = $router;
//     }

//     public function onAccessDenied(ExceptionEvent $event)
//     {
//         $exception = $event->getThrowable();

//         if ($exception instanceof AccessDeniedException) {
//             $url = $this->router->generate('home');
//             $response = new RedirectResponse($url);

//             $event->setResponse($response);
//         }
//     }
// }