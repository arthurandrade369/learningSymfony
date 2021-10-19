<?php

namespace App\Controller;

use App\Entity\Account;
use App\Model\View;
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
     * @return AccountUserProvider
     */
    public function getAccountUserProvider(): AccountUserProvider
    {
        return $this->accountUserProvider;
    }

    /**
     * @param mixed $body
     * @param int|null $statusCode
     * @param array $header
     * @return View
     */
    public function view($body = null, $statusCode = null, $header = []): View
    {
        $view = new View($body, $statusCode, $header);
        $view->setGroups(array('Default'));
        return $view;
    }

    /**
     * @param View $view
     * @return string
     */
    public function handleView(View $view): Response
    {
        $context = null;
        if ($view->getGroups()) {
            $context = SerializationContext::create();
            $context->setGroups($view->getGroups());
        }

        $view->setHeader(['Content-Type' => $view->getContentType()]);
        $data = $this->serializer($view->getBody(), $context);

        $response = new Response();
        $response->setStatusCode($view->getStatusCode());
        $response->headers->add($view->getHeader());
        $response->setContent($data);

        return $response;
    }

    /**
     * @param Request $request
     * @param string $entity
     * @param string|null $contextGroup
     * @return mixed
     */
    public function getObjectPerRequest(Request $request, string $entity, ?string $contextGroup = null)
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

        $responseBody = array(
            'code' => $exceptionCode,
            'message' => $exception->getMessage(),
            'address' => $request->getClientIp()
        );

        $view = $this->view($responseBody, $exceptionCode);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param mixed $data
     * @param string|null $contextGroup
     * @return Response
     */
    public function showResponse(Request $request, $data, $contextGroup = null): Response
    {
        if (!is_object($data)) {
            throw new Exception("Function must receive an object", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $view = $this->view($data, Response::HTTP_OK);
        $view->setGroups(array('Show'));

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param array $data
     * @param string|null $contextGroup
     * @return string
     */
    public function dataTableResponse(Request $request, array $data, $contextGroup = null): Response
    {
        if (!is_array($data)) {
            throw new Exception("Function must receive an array", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $body = array(
            "iTotalRecords" => count($data),
            "aaData" => $data
        );

        $view = $this->view($body, Response::HTTP_OK);
        $view->setGroups(array('List'));

        return $this->handleView($view);
    }

    public function serializer($data, $contextGroup = null): string
    {
        $format = 'json';
        $context = null;

        if ($contextGroup) {
            $context = SerializationContext::create();
            $context->setGroups($contextGroup);
        }

        return $this->getSerializer()->serialize($data, $format, $context);
    }

    public function deserializer($data, $entity, $contextGroup = null)
    {
        $format = 'json';
        $context = null;

        if ($contextGroup) {
            $context = DeserializationContext::create();
            $context->setGroups($contextGroup);
        }

        return $this->getSerializer()->deserialize($data, $entity, $format, $context);
    }

    /**
     * @param string $token
     * @return string
     */
    public static function separateAuthorization(string $token): string
    {
        $varToken = explode(' ', $token, 2);

        return $varToken[1];
    }

    /**
     * @param string $token
     * @return array|null
     */
    public static function separateToken(string $token): ?array
    {
        $varToken = explode('_', $token);

        if (!$varToken[0] && !$varToken[1]) return null;

        return $varToken;
    }

    /**
     * @param $entity
     * @throws Exception
     */
    public static function errorNotFoundResponse($entity)
    {
        $message = substr($entity, 11) . ' Not Found';
        throw new Exception($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param string $message
     * @throws Exception
     */
    public static function errorUnProcessableEntityResponse(string $message)
    {
        throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param $entity
     * @throws Exception
     */
    public static function errorInternalServerResponse($entity)
    {
        $message = 'Expected a instance of ' . substr($entity, 11) . ' class';
        throw new Exception($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
