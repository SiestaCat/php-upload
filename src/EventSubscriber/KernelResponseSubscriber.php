<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelResponseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event):void
    {
        return;//Dsiabled, Not working
        if($event->getRequest()->getMethod() === 'OPTIONS')
        {
            $event->setResponse
            (
                new Response
                (
                    null,
                    204,
                    [
                        'Access-Control-Allow-Origin' => '*'
                    ]
                )
            );
        }
    }
}