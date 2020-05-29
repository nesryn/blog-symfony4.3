<?php
/**
 * Created by PhpStorm.
 * User: nesri
 * Date: 26/05/2020
 * Time: 23:25
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthoredEntitySubscriber implements EventSubscriberInterface
{

    private $storage;
    public function __construct(TokenStorageInterface $storage)
    {
        $this->storage= $storage;
    }

    public static function getSubscribedEvents()
    {
        return [
          KernelEvents::VIEW => ['getAuthUser' , EventPriorities::PRE_WRITE]
        ];
    }

    public function getAuthUser (ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        $token = $this->storage->getToken();
        if (null === $token) {
            return;
        }

        /** @var UserInterface $author */
        $author = $this->storage->getToken()->getUser();

       // dump($entity,$method,$author);die;
        if ((!$entity instanceof BlogPost && !$entity instanceof Comment) || Request::METHOD_POST !== $method)
        {return;}
        $entity->setAuthor($author);

      //  dump($entity);exit();

    }
}