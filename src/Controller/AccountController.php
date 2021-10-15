<?php

namespace App\Controller;

use App\Controller\AbstractCrudController;
use App\Entity\Account;
use App\Repository\AccountRepository;
use DateTimeZone;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/service/v1/account", name="account_")
 */
class AccountController extends AbstractCrudController
{
    /**
     * @Route("", name="register",  methods={"POST"})
     */
    public function register(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            
            $account = $this->getObjectPerRequest($request, Account::class);
            if(!$account instanceof Account){
                throw new Exception("Error Processing Request", 500);
            }
            
            if (!$this->checkIsEmail($account->getEmail())) {
                $em->persist($account);
                $em->flush();
            } else {
                throw new Exception("Email already exists", Response::HTTP_CONFLICT);
            }

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function listAccounts(Request $request): Response
    {
        // $this->isGranted('ROLE_ADMIN');

        try {
            $em = $this->getDoctrine()->getManager();

            $account = $em->getRepository(Account::class)->findAll();

            return $this->dataTableResponse($request, $account, 'List');
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function showAccount($Id, Request $request)
    {
    }

    /**
     * @Route("", name="update", methods={"UPDATE"})
     */
    public function updateAccount($Id, Request $request)
    {
    }

    /**
     * @Route("", name="delete", methods={"DELETE"})
     */
    public function deleteAccount($id, Request $request)
    {
    }

    public function checkIsEmail(string $email): bool
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository(Account::class);
        if(!$repo instanceof AccountRepository) throw new Exception("Error Processing Request", 500);
        
        $data = $repo->getByEmail($email);
        if (count($data) > 0) return true;

        return false;
    }
}
