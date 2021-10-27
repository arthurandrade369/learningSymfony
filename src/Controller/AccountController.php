<?php

namespace App\Controller;

use App\Controller\AbstractCrudController;
use App\Entity\Account;
use App\Repository\AccountRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends AbstractCrudController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        return $this->list(Account::class, $request);
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function showAction(int $id, Request $request): Response
    {
        return $this->show($id, Account::class, $request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();
            
            $account = $this->getObjectPerRequest($request, Account::class);
            if(!$account instanceof Account){
                AbstractController::errorInternalServerResponse(Account::class);
            }
            
            if (!$this->checkIsEmail($account->getEmail())) {
                $em->persist($account);
                $em->flush();
            } else {
                throw new Exception("Email already exists", Response::HTTP_CONFLICT);
            }

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
        return $this->update($id, Account::class, $request);
    }

    /**
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function deleteAction(int $id, Request $request): Response
    {
        return $this->delete($id, Account::class, $request);
    }

    /**
     * @param string $email
     * @return boolean
     */
    public function checkIsEmail(string $email): bool
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository(Account::class);
        if(!$repo instanceof AccountRepository) throw new Exception("Error Processing Request", 500);
        
        $account = $repo->getAccountByEmail($email);
        if ($account) return true;

        return false;
    }
}
