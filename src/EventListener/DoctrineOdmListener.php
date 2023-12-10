<?php declare( strict_types = 1 );

namespace App\EventListener;

use App\EventListener\Document\RequestListener;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineOdmListener
{
    public function __construct
    (
        private array $documents_listeners,
        private ContainerInterface $container
    ){}

    public function __call($name, $arguments)
    {
        return $this->callEvent($name, $arguments[0]);
    }

    private function callEvent(string $method_name, LifecycleEventArgs $event):void
    {
        $document_class = get_class($event->getObject());

        if(!array_key_exists($document_class, $this->documents_listeners))
        {
            //Show exception?
            return;
        }

        $document_listener_service = $this->container->get($this->documents_listeners[$document_class]);

        if(!method_exists($document_listener_service, $method_name))
        {
            //Show exception?
            return;
        }

        call_user_func_array([$document_listener_service, $method_name], [$event->getObject()]);
    }
}