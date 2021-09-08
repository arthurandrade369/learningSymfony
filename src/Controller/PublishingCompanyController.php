<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\PublishingCompany;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/publishing/company", name="publishing_company_")
 */
class PublishingCompanyController extends AbstractController
{
    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function listCompanies(): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $company = $em->getRepository(PublishingCompany::class)->findAll();

            return $this->json($company);
        } catch (Exception $exception) {
            return new Response('Error: ' . $exception);
        }
    }

    /**
     * @Route("/{companyId}", name="show", methods={"GET"})
     */
    public function showCompany($companyId)
    {
        try {
            $company = $this->getDoctrine()->getRepository(PublishingCompany::class)->find($companyId);

            return $this->json($company);
        } catch (Exception $exception) {
            return new Response("Error " . $exception);
        }
    }

    /**
     * @Route("", name="create", methods={"POST"})
     */
    public function createCompany(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $data = $request->request->all();

            $company = new PublishingCompany();
            $company->setName($data['name']);

            $em->persist($company);
            $em->flush();

            return $this->json([
                'Response' => "Cadastrado com sucesso",
                'data' => $company,
            ]);
        } catch (Exception $exception) {
            return new Response("Error " . $exception);
        }
    }

    /**
     * @Route("/{companyId}", name="update", methods={"PUT"})
     */
    public function updateCompany($companyId, Request $request)
    {
        try {
            $data = $request->request->all();

            $doctrine = $this->getDoctrine();

            $company = $doctrine->getRepository(PublishingCompany::class)->find($companyId);
            $company->setName($data['name']);

            $em = $doctrine->getManager();
            $em->flush();

            return new Response("Editora atualizada com sucesso!");
        } catch (Exception $exception) {
            return new Response("Error " . $exception);
        }
    }

    /**
     * @Route("/{companyId}", name="delete", methods={"DELETE"})
     */
    public function deleteCompany($companyId)
    {
        try {
            $doctrine = $this->getDoctrine();

            $book = $doctrine->getRepository(PublishingCompany::class)->find($companyId);

            $em = $doctrine->getManager();
            $em->remove($book);
            $em->flush();

            return new Response("Editora deletado com sucesso!");
        } catch (Exception $exception) {
            return new Response("Error " . $exception);
        }
    }
}
