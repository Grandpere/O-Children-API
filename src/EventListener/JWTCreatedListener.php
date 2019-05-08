<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{
    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Personalize jwt token with userId
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();
        $request = $this->requestStack->getCurrentRequest();
    
        $payload       = $event->getData();
        $payload['userId'] = $user->getId();
    
        $event->setData($payload);
        
        $header        = $event->getHeader();
        $header['cty'] = 'JWT';
    
        $event->setHeader($header);
    }
}