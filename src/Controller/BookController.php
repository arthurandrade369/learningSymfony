<?php

namespace App\Controller;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Book;
use DateTime;
use Exception;

/**
 * @Route("/book", name="book_")
 */
class BookController extends AbstractController
{

    /**
     *
     * @Route("", name="list", methods={"GET"}) 
     */
    public function booksList(): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $book = $em->getRepository(Book::class)->findAll();

            return $this->json([
                "data" => $book,
            ]);
        } catch (Exception $exception) {
            return new Response('Error: ' . $exception);
        }
    }

    /**
     * 
     * @Route("/{bookId}", name="show", methods={"GET"})
     */
    public function bookShow($bookId)
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($bookId);

        return $this->json(['data' => $book]);
    }

    /**
     * 
     * @Route("/", name="create", methods={"POST"})
     */
    public function createBook(Request $request)
    {
        try {

            $em = $this->getDoctrine()->getManager();

            $data = $request->request->all();

            $book = new Book();
            $book->setBookTitle($data['title']);
            $book->setBookAuthor($data['author']);
            $book->setQuantityPages($data['quantity_pages']);
            $book->setReleaseDate(new DateTime(($data['release_date'])));

            $em->persist($book);
            $em->flush();

            return $this->json($book);
        } catch (Exception $exception) {
            return new Response('Error: ' . $exception);
        }
    }

    /**
     * 
     * @Route("/{bookId}", name="update", methods={"PUT"})
     */
    public function updateBook($bookId, Request $request)
    {
        try {
            $data = $request->request->all();

            $doctrine = $this->getDoctrine();

            $book = $doctrine->getRepository(Book::class)->find($bookId);

            if ($request->request->has('title'))
                $book->setBookTitle($data['title']);
            if ($request->request->has('author'))
                $book->setBookAuthor($data['author']);
            if ($request->request->has('quantity_pages'))
                $book->setQuantityPages($data['quantity_pages']);
            if ($request->request->has('release_date'))
                $book->setReleaseDate(new DateTime($data['release_date']));

            $em = $doctrine->getManager();
            $em->flush();

            return new Response("Livro atualizado com sucesso");
        } catch (Exception $exception) {
            return new Response('Error: ' . $exception);
        }
    }

    /**
     * 
     * @Route("/{bookId}", name="delete", methods={"DELETE"})
     * @return void
     */
    public function deleteBook($bookId)
    {
        try {
            $doctrine = $this->getDoctrine();
            
            $book = $doctrine->getRepository(Book::class)->find($bookId);

            $em = $doctrine->getManager();
            $em->remove($book);
            $em->flush();

            return new Response("Livro apagado com sucesso");

        } catch (Exception $exception) {
            return new Response('Error: ' . $exception);
        }
    }
}
