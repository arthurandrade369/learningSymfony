<?php

namespace App\Controller;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Book;
use App\Repository\BookRepository;
use DateTime;
use Exception;

/**
 * @Route("/service/v1/book", name="book_")
 */
class BookController extends AbstractController
{

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function listBooks(Request $request): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $repo = $em->getRepository(Book::class);
            if (!$repo instanceof BookRepository) throw new Exception("Error Processing Entity", 500);

            $book = $repo->findEverything();

            return new Response($this->serializer($book));
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("/{bookId}", name="show", methods={"GET"})
     */
    public function showBook($bookId, Request $request): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $repo = $em->getRepository(Book::class);
            if (!$repo instanceof BookRepository) throw new Exception("Error Processing Entity", 500);

            $book = $repo->findOnlyOne($bookId);

            return new Response($this->serializer($book, 'json', ['Show']));
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function createBook(Request $request)
    {
        try {

            $em = $this->getDoctrine()->getManager();

            $data = $request->request->all();

            $book = new Book();
            $book->setTitle($data['title']);
            $book->setAuthor($data['author']);
            $book->setQuantityPages($data['quantity_pages']);
            $book->setReleaseDate(new DateTime(($data['release_date'])));
            $book->setPublisher($data['publishing_company']);

            $em->persist($book);
            $em->flush();

            return $this->json($book);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("/{bookId}", name="update", methods={"PUT"})
     */
    public function updateBook($bookId, Request $request)
    {
        try {
            $data = $request->request->all();

            $doctrine = $this->getDoctrine();

            $book = $doctrine->getRepository(Book::class)->find($bookId);

            // if ($request->request->has('title'))
            //     $book->setBookTitle($data['title']);
            // if ($request->request->has('author'))
            //     $book->setBookAuthor($data['author']);
            // if ($request->request->has('quantity_pages'))
            //     $book->setQuantityPages($data['quantity_pages']);
            // if ($request->request->has('release_date'))
            //     $book->setReleaseDate(new DateTime($data['release_date']));
            // if ($request->request->has('publishing_company'))
            //     $book->setPublisherId($data['publishing_company']);
            $book->setObject($data);

            $em = $doctrine->getManager();
            $em->flush();

            return new Response("Livro atualizado com sucesso");
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("/{bookId}", name="delete", methods={"DELETE"})
     */
    public function deleteBook($bookId, Request $request)
    {
        try {
            $doctrine = $this->getDoctrine();

            $book = $doctrine->getRepository(Book::class)->find($bookId);

            $em = $doctrine->getManager();
            $em->remove($book);
            $em->flush();

            return new Response("Livro apagado com sucesso");
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }
}
