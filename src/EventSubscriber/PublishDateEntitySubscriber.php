<?php
/**
 * Created by PhpStorm.
 * User: nesri
 * Date: 29/05/2020
 * Time: 07:01
 */

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\AuthoredEntityInterface;
use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\PublishedDateEntityInterface;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PublishDateEntitySubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDataPublished' , EventPriorities::PRE_WRITE]
        ];
    }

    public function setDataPublished (ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();


        /** @var UserInterface $author */


        // dump($entity,$method,$author);die;
        if ((!$entity instanceof PublishedDateEntityInterface) || Request::METHOD_POST !== $method)
        {return;}
        $entity->setPublished(new \DateTime());

        //  dump($entity);exit();

    }
}