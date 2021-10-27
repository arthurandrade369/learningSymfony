<?php

namespace App\Listener;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ExceptionListener
{

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedHttpException) {
            if ($exception->getStatusCode() === 403) {
                $responseCode = Response::HTTP_UNAUTHORIZED;
            } else if ($exception->getStatusCode() > 0) {
                $responseCode = $exception->getStatusCode();
            } else {
                $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        } else if ($exception instanceof HttpExceptionInterface) {
            $responseCode = $exception->getStatusCode() > 0 ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        } else if ($exception instanceof Exception) {
            $responseCode = $exception->getCode() > 0 ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        } else {
            $responseCode = $exception->getCode() > 0 ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $response = $this->setResponse($exception->getMessage(), $responseCode);
        $event->setResponse($response);
    }

    /**
     * @param $message
     * @param $code
     * @return Response
     */
    private function setResponse($message, $code): Response {
        $message = array(
            'code' => $code,
            'message' => $message
        );

        $response = new Response();
        $response->setStatusCode($code);
        $response->setContent(json_encode($message));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
