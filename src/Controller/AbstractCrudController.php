<?php

namespace App\Controller;

use Exception;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractCrudController extends AbstractController
{

    public function list($entity, Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $data = $em->getRepository($entity)->findAll();

            return new Response($this->serializer($data));
        } catch (Exception $exception) {
            return $this->exceptionResponse($request,$exception);
        }
    }

    public function show($id, $entity, Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $data = $em->getRepository($entity)->findBy($id);

            return new Response($this->serializer($data));
        } catch (Exception $exception) {
            return $this->exceptionResponse($request,$exception);
        }
    }

    public function create(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            
        } catch (Exception $exception) {
            return $this->exceptionResponse($request,$exception);
        }
    }
}
