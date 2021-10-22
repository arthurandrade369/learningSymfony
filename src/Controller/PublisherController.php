<?php

namespace App\Controller;

use App\Controller\AbstractCrudController;
use App\Entity\Publisher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/service/v1/publisher", name="publisher_")
 */
class PublisherController extends AbstractCrudController
{
    /**
     * @Route("", name="list", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function listPublishers(Request $request)
    {
        return $this->list(Publisher::class, $request);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function showPublisher(int $id, Request $request)
    {
        return $this->show($id, Publisher::class,$request);
    }

    /**
     * @Route("", name="create", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function createPublisher(Request $request)
    {
        return $this->create(Publisher::class, $request);
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function updateCompany(int $id, Request $request)
    {
        return $this->update($id, Publisher::class, $request);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function deleteCompany(int $id, Request $request)
    {
        return $this->delete($id, Publisher::class, $request);
    }
}
