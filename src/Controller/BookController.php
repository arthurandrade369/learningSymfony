<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Book;
use App\Entity\Publisher;
use Exception;
use App\Controller\AbstractCrudController;

class BookController extends AbstractCrudController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        return $this->list(Book::class, $request);
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function showAction(int $id, Request $request): Response
    {
        return $this->show($id, Book::class, $request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $book = $this->getObjectPerRequest($request, Book::class);
            if (!$book instanceof Book) AbstractController::errorInternalServerResponse(Publisher::class);
            if (!$book->getPublisher()) AbstractController::errorUnProcessableEntityResponse('publisher is required');

            $publisher = $this->getDoctrine()->getRepository(Publisher::class)->find($book->getPublisher()->getId());
            if (!$publisher) AbstractController::errorNotFoundResponse(Publisher::class);
            if (!$publisher instanceof Publisher) AbstractController::errorInternalServerResponse(Publisher::class);

            $book->setPublisher($publisher);
            $em->persist($book);
            $em->flush();

            return new Response(null, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function updateAction(int $id, Request $request): Response
    {
        try {
            $doctrine = $this->getDoctrine();

            $data = $this->getObjectPerRequest($request, Book::class);
            $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
            if (!$book) AbstractController::errorNotFoundResponse(Book::class);
            if (!$book instanceof Book) AbstractController::errorInternalServerResponse(Book::class);

            $book->setObject($data);
            $em = $doctrine->getManager();
            $em->flush();

            return new Response(null, Response::HTTP_ACCEPTED);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function deleteAction(int $id, Request $request): Response
    {
        return $this->delete($id, Book::class, $request);
    }
}
