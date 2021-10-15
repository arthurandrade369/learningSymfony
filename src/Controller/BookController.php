<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Book;
use App\Entity\Publisher;
use Exception;
use App\Controller\AbstractCrudController;
use App\Entity\OAuth2RefreshToken;

/**
 * @Route("/service/v1/book", name="book_")
 */
class BookController extends AbstractCrudController
{

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function listAction(Request $request)
    {
        return $this->list(Book::class, $request);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function showAction($id, Request $request)
    {
        return $this->show($id, Book::class, $request);
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function createAction(Request $request)
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
     * @Route("/{id}", name="update", methods={"PUT"})
     */
    public function updateAction($id, Request $request)
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
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function deleteAction($id, Request $request)
    {
        return $this->delete($id, Book::class, $request);
    }
}
