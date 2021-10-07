<?php

namespace App\Controller;

use App\Entity\Account;
use App\Provider\AccountUserProvider;
use Exception;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerInterface;


class AbstractController extends BaseController
{
    private SerializerInterface $serializer;
    private AccountUserProvider $accountUserProvider;

    public function __construct(SerializerInterface $serializer, AccountUserProvider $accountUserProvider)
    {
        $this->serializer = $serializer;
        $this->accountUserProvider = $accountUserProvider;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * Get the value of AccountUserProvider
     * @return AccountUserProvider
     */
    public function getAccountUserProvider(): AccountUserProvider
    {
        return $this->accountUserProvider;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param string $entity
     * @param [type] $contextType
     * @return mixed
     */
    public function getObjectPerRequest(Request $request, string $entity, $contextGroup = null)
    {
        $format = 'json';

        $mBodyData = array();
        $body = $request->getContent();

        if (!empty($body)) {
            $mBodyData = json_decode($body, true);
        }

        $mData = array_merge(
            $request->request->all(),
            empty($mBodyData) ? array() : $mBodyData
        );

        $data = json_encode($mData);

        $context = null;

        if ($contextGroup) {
            $context = DeserializationContext::create();
            $context->setGroups($contextGroup);
        }

        return $this->getSerializer()->deserialize($data, $entity, $format, $context);
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
            throw new Exception("An array is required", Response::HTTP_NOT_ACCEPTABLE);
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
            throw new Exception("An object is required", Response::HTTP_NOT_ACCEPTABLE);
        }

        $body = array(
            "iTotalRecords" => count($data),
            "aaData" => $data
        );

        return $this->getSerializer()->serialize($body, 'json');
    }

    public function serializer($data, $contextGroup = null)
    {
        $format = 'json';
        $context = null;

        if ($contextGroup) {
            $context = SerializationContext::create();
            $context->setGroups($contextGroup);
        }

        return $this->getSerializer()->serialize($data, $format, $context);
    }

    public function deserializer($data, $type, $contextGroup = null)
    {
        $format = 'json';
        $context = null;

        if ($contextGroup) {
            $context = DeserializationContext::create();
            $context->setGroups($contextGroup);
        }

        return $this->getSerializer()->deserialize($data, $type, $format, $context);
    }

    public function abstractResponse($data, $contextGroup = null)
    {
        return new Response($this->serializer($data, $contextGroup));
    }

    public static function separateAuthorization($token)
    {
        return strpos($token,7);
    }

    public static function separateToken($token)
    {
    }
}
