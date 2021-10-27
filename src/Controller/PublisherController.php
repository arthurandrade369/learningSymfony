<?php

namespace App\Controller;

use App\Controller\AbstractCrudController;
use App\Entity\Publisher;
use Symfony\Component\HttpFoundation\Request;

class PublisherController extends AbstractCrudController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        return $this->list(Publisher::class, $request);
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function showAction(int $id, Request $request)
    {
        return $this->show($id, Publisher::class,$request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        return $this->create(Publisher::class, $request);
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function updateAction(int $id, Request $request)
    {
        return $this->update($id, Publisher::class, $request);
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function deleteAction(int $id, Request $request)
    {
        return $this->delete($id, Publisher::class, $request);
    }
}
