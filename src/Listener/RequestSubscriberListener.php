<?php

namespace App\Listener;

use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestSubscriberListener implements EventSubscriberInterface
{

    /**
     * @return string[]
     */
    public static function getAcceptContentTypes(): array {
        return ['application/x-www-form-urlencoded', 'multipart/form-data', 'application/json'];
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    /**
     * @param RequestEvent $event
     * @return void
     * @throws Exception
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        // don't do anything if it's not the master request
        if (!$event->isMasterRequest()) return;

        $request = $event->getRequest();
        if ($request->getMethod() === 'POST' || $request->getMethod() === 'PUT') {
            if (!$request->headers->get('Content-Type')) {
                throw new Exception('Content-Type of header is not set, server requires it', Response::HTTP_PRECONDITION_FAILED);
            }
            if (!str_contains($request->headers->get('Content-Type'), 'multipart/form-data')) {
                if (!in_array($request->headers->get('Content-Type'), self::getAcceptContentTypes())) {
                    throw new Exception('Content-type not acceptable', Response::HTTP_PRECONDITION_FAILED);
                }
            }
        }
    }
}
