<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController AS BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/abstract", name="abstract_")
 */
class AbstractController extends BaseController
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Exception $exception
     * @return Response
     */
    public function exceptionResponse(Request $request, Exception $exception): Response
    {
        $exceptionCode = $exception->getCode() > 0 ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        $responseBody = json_encode(array(
            'code' => $exceptionCode,
            'message' => $exception->getMessage(),
            'address' => $request->getClientIp()
        ));

        $response = new Response();
        $response->setContent($responseBody);
        $response->setStatusCode($exceptionCode);
        $response->headers->set('Content-Type', 'application/json');
        $response->send();

        return $response;
    }
}
