<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Service\FileService;
use App\Entity\User;

class  EasyAdminSubscriber implements EventSubscriberInterface {
    
    private $service;

    public function __construct(FileService $fileService) {
        $this->service = $fileService;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_persist' => array('setAvatarToUser'),
            'easy_admin.pre_update' => array('setAvatarToUser'),
        );
    }

    public function setAvatarToUser(GenericEvent $event)
    {
        $entity = $event->getSubject();
        $avatar = $entity->getAvatar();
        // $avatar = $this->service->savePicture($entity->getAvatar(), 'public/images/avatar');
        // dd($avatar);

    }
}