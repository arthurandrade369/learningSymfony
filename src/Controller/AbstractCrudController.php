<?php

namespace App\Controller;

use Exception;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractCrudController extends AbstractController
{

    public function list($entity, Request $request): Response
    {
        try {
            $data = $this->getDoctrine()->getRepository($entity)->findAll();
  
            return $this->abstractResponse($request, $data);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    public function show($id, $entity, Request $request): Response
    {
        try {
            $data = $this->getDoctrine()->getRepository($entity)->find($id);
            if (!$data) AbstractController::errorNotFoundResponse($entity);
            if (!$data instanceof $entity) AbstractController::errorInternalServerResponse($entity);

            return $this->abstractResponse($request, $data);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    public function create($entity, Request $request): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $data = $this->getObjectPerRequest($request, $entity);
            if (!$data instanceof $entity) AbstractController::errorInternalServerResponse($entity);
            $em->persist($data);
            $em->flush();

            return $this->abstractResponse($request, $data);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    public function update($id, $entity, Request $request): Response
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

            return $this->abstractResponse($request, $object);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    public function delete($id, $entity, Request $request): Response
    {
        try {
            $doctrine = $this->getDoctrine();

            $data = $doctrine->getRepository($entity)->find($id);
            if (!$data) AbstractController::errorNotFoundResponse($entity);
            if (!$data instanceof $entity) AbstractController::errorInternalServerResponse($entity);

            $em = $doctrine->getManager();
            $em->remove($data);
            $em->flush();

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @param [type] $id
     * @param [type] $entity
     * @param Request $request
     * @return Response
     */
    public function updateEnabledAction($id, $entity, Request $request): Response
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
}
