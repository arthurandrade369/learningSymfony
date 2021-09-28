<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;


class AbstractController extends BaseController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * @param Request $request
     * @param Exception $exception
     * @return Response
     */
    public function exceptionResponse(Request $request, Exception $exception): Response
    {
        $exceptionCode = $exception->getCode() > 0 ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        $responseBody = $this->serializer(array(
            'code' => $exceptionCode,
            'message' => $exception->getMessage(),
            'address' => $request->getClientIp()
        )); 

        $response = new Response();
        $response->setContent($responseBody);
        $response->setStatusCode($exceptionCode);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param Request $request
     * @param [type] $data
     * @param [type] $contextGroup
     * @return void
     */
    public function showResponse(Request $request, $data, $contextGroup = null)
    {
        if (!is_object($data)) {
            throw new Exception("Error Processing Request", Response::HTTP_NOT_ACCEPTABLE);
        }

        return $this->getSerializer()->serialize($data, 'json');
    }

    /**
     * @param Request $request
     * @param [type] $data
     * @param [type] $contextGroup
     * @return string
     */
    public function dataTableResponse(Request $request, $data, $contextGroup = null): string
    {
        if (!is_array($data)) {
            throw new Exception("an object is needed", Response::HTTP_NOT_ACCEPTABLE);
        }

        $body = array(
            "iTotalRecords" => count($data),
            "aaData" => $data
        );

        return $this->getSerializer()->serialize($body, 'json');
    }

    public function serializer($data, $format = 'json', $contextType = null)
    {
        return $this->getSerializer()->serialize($data, $format, $contextType);
    }

    public function deserializer($data, $type, $format = 'json', $contextType = null)
    {
        return $this->getSerializer()->deserialize($data, $type, $format, $contextType);
    }
}
