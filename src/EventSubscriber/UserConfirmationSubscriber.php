<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\UserConfirmation;
use App\Security\UserConfirmationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserConfirmationSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserConfirmationService
     */
    private $userConfirmationService;

    public function __construct(
        UserConfirmationService $userConfirmationService
    ) {
        $this->userConfirmationService = $userConfirmationService;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['confirmUser', EventPriorities::POST_VALIDATE]
        ];

    }

    public function confirmUser(ViewEvent $event)
    {
        $request = $event->getRequest();
       // dump($event);die;
        if ('api_user_confirmations_post_collection' !==
            $request->get('_route')) {
            return;
        }

        $confirmationToken = $event->getControllerResult();
        //dump($confirmationToken->getConfirmationToken());exit();
        // $confirmationTokenValue=$confirmationToken->getConfirmationToken();
        $this->userConfirmationService->confirmUser(
            $confirmationToken->getConfirmationToken());
      //  dump('h');die;

        $event->setResponse(new JsonResponse(null, Response::HTTP_OK));
    }
}