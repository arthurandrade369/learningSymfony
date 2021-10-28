<?php

namespace App\Controller;

use Exception;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractCrudController extends AbstractController
{

    /**
     * @param string $entity
     * @param Request $request
     * @return Response
     */
    public function list(string $entity, Request $request): Response
    {
        try {
            $data = $this->getDoctrine()->getRepository($entity)->findAll();

            return $this->listResponse($request, $data);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @param integer $id
     * @param string $entity
     * @param Request $request
     * @return Response
     */
    public function show(int $id, string $entity, Request $request): Response
    {
        try {
            $data = $this->getDoctrine()->getRepository($entity)->find($id);
            if (!$data) AbstractController::errorNotFoundResponse($entity);
            if (!$data instanceof $entity) AbstractController::errorInternalServerResponse($entity);

            return $this->showResponse($request, $data);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @param string $entity
     * @param Request $request
     * @return Response
     */
    public function create(string $entity, Request $request): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $data = $this->getObjectPerRequest($request, $entity);
            if (!$data instanceof $entity) AbstractController::errorInternalServerResponse($entity);
            $em->persist($data);
            $em->flush();

            return new Response(null, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @param integer $id
     * @param string $entity
     * @param Request $request
     * @return Response
     */
    public function update(int $id, string $entity, Request $request): Response
    {
        try {
            $doctrine = $this->getDoctrine();

            $data = $this->getObjectPerRequest($request, $entity);
            $object = $this->getDoctrine()->getRepository($entity)->find($id);
            if (!$object) AbstractController::errorNotFoundResponse($entity);
            if (!$object instanceof $entity) AbstractController::errorInternalServerResponse($entity);

            $object->setObject($data);

            $em = $doctrine->getManager();
            $em->flush();

            return new Response(null, Response::HTTP_ACCEPTED);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @param int $id
     * @param string $entity
     * @param Request $request
     * @return Response
     */
    public function updateEnabled(int $id, string $entity, Request $request): Response
    {
        try {
            $doctrine = $this->getDoctrine();

            $object = $this->getDoctrine()->getRepository($entity)->find($id);
            if (!$object) AbstractController::errorNotFoundResponse($entity);
            if (!$object instanceof $entity) AbstractController::errorInternalServerResponse($entity);

            $object->setEnabled(!$object->getEnabled());

            $em = $doctrine->getManager();
            $em->flush();

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @param integer $id
     * @param string $entity
     * @param Request $request
     * @return Response
     */
    public function delete(int $id, string $entity, Request $request): Response
    {
        try {
            $doctrine = $this->getDoctrine();

            $data = $doctrine->getRepository($entity)->find($id);
            if (!$data) AbstractController::errorNotFoundResponse($entity);
            if (!$data instanceof $entity) AbstractController::errorInternalServerResponse($entity);

            $em = $doctrine->getManager();
            $em->remove($data);
            $em->flush();

            return new Response(null, Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }
}
