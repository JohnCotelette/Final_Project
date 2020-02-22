<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Service\UserService;

class  EasyAdminSubscriber implements EventSubscriberInterface {
    
    private $service;

    public function __construct(UserService $userService) {
        $this->service = $userService;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_persist' => array('CryptPassword'),
        );
    }

    public function CryptPassword(GenericEvent $event)
    {
        $entity = $event->getSubject();
        $entity = $this->service->cryptPassword($entity);
    }
}