<?php

namespace App\Controller;

use App\Controller\AbstractCrudController;
use App\Entity\Publisher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PublisherController extends AbstractCrudController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        return $this->list(Publisher::class, $request);
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function showAction(int $id, Request $request): Response
    {
        return $this->show($id, Publisher::class,$request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        return $this->create(Publisher::class, $request);
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function updateAction(int $id, Request $request): Response
    {
        return $this->update($id, Publisher::class, $request);
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function deleteAction(int $id, Request $request): Response
    {
        return $this->delete($id, Publisher::class, $request);
    }
}
