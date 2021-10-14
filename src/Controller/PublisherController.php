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
     */
    public function listPublishers(Request $request)
    {
        return $this->list(Publisher::class, $request);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function showPublisher($id, Request $request)
    {
        return $this->show($id, Publisher::class,$request);
    }

    /**
     * @Route("", name="create", methods={"POST"})
     */
    public function createPublisher(Request $request)
    {
        return $this->create(Publisher::class, $request);
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     */
    public function updateCompany($id, Request $request)
    {
        return $this->update($id, Publisher::class, $request);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function deleteCompany($id, Request $request)
    {
        return $this->delete($id, Publisher::class, $request);
    }
}
