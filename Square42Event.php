<?php

namespace Plugin\Square42;

use Eccube\Event\TemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Square42Event implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'Shopping/index.twig' => 'onShoppingIndexTwig',
        ];
    }

    public function onShoppingIndexTwig(TemplateEvent $event)
    {
        $event->addSnippet('@Square42/credit.twig');
    }
}

