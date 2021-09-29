<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\Account;
use App\Repository\AccountRepository;
use DateTimeZone;
use Exception;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/service/v1/account", name="account_")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("", name="register",  methods={"POST"})
     */
    public function register(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $account = $this->getObjectPerRequest($request, Account::class);
            $account->setName($data['name']);
            if (!$this->checkIsEmail($data['email'])) {
                $account->setEmail($data['email']);
            } else {
                throw new Exception("Email already exists", Response::HTTP_CONFLICT);
            }
            $account->setPassword($this->encodePassword($data['password'], 'md5'));
            $account->setType($data['type']);
            $account->setCreatedAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            $account->setModifiedAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));

            $em->persist($account);
            $em->flush();

            return new JsonResponse([
                'message' => 'Conta criada com sucesso',
                'data' => $this->serializer($account, 'json')
            ]);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function listAccounts(Request $request): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $account = $em->getRepository(Account::class)->findAll();

            return new Response($this->dataTableResponse($request, $account));
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("/{id}", name=("show"), methods={"GET"})
     */
    public function showAccount($Id, Request $request)
    {
    }

    /**
     * @Route("", name=("show"), methods={"UPDATE"})
     */
    public function updateAccount($Id, Request $request)
    {
    }

    /**
     * @Route("", name("delete"), methods={"DELETE"})
     */
    public function deleteAccount($id, Request $request)
    {
    }

    public function encodePassword($password)
    {
        return $password;
    }

    public function checkIsEmail($email)
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository(Account::class)->findBy($email);

        if (count($data) > 0) return false;

        return true;
    }
}
