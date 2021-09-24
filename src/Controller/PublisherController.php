<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/publishing/company", name="publishing_company_")
 */
class PublisherController extends AbstractController
{
    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function listPublishers(Request $request): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $publisher = $em->getRepository(Publisher::class)->findAll();

            return new Response($this->serializer($publisher));
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("/{publisherId}", name="show", methods={"GET"})
     */
    public function showPublisher($publisherId, Request $request)
    {
        try {
            $publisher = $this->getDoctrine()->getRepository(Publisher::class)->find($publisherId);

            return new Response($this->serializer($publisher));
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("", name="create", methods={"POST"})
     */
    public function createPublisher(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        try {
            $em = $this->getDoctrine()->getManager();

            $data = $request->request->all();

            $publisher = new Publisher();
            $publisher->setName($data['name']);

            $em->persist($publisher);
            $em->flush();

            return new Response($this->serializer($publisher));
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("/{companyId}", name="update", methods={"PUT"})
     */
    public function updateCompany($companyId, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        try {
            $data = $request->request->all();

            $doctrine = $this->getDoctrine();

            $company = $doctrine->getRepository(Publisher::class)->find($companyId);
            $company->setName($data['name']);

            $em = $doctrine->getManager();
            $em->flush();

            return new Response("Editora atualizada com sucesso!");
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("/{companyId}", name="delete", methods={"DELETE"})
     */
    public function deleteCompany($companyId, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        try {
            $doctrine = $this->getDoctrine();

            $book = $doctrine->getRepository(Publisher::class)->find($companyId);

            $em = $doctrine->getManager();
            $em->remove($book);
            $em->flush();

            return new Response("Editora deletado com sucesso!");
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }
}
