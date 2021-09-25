<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\Account;
use DateTimeZone;
use Exception;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Exclusion\GroupsExclusionStrategy;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(Request $request): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $account = $em->getRepository(Account::class)->findAll();

            return new Response($this->serializer($account, 'json', SerializationContext::create()->setGroups('List')));
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @Route("/register", name="register",  methods={"POST"})
     */
    public function register(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $data = $request->request->all();

            $account = new Account;
            $account->setName($data['name']);
            $account->setEmail($data['email']);
            $account->setPassword($data['password']);
            $account->setType($data['type']);
            $account->setCreatedAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));
            $account->setModifiedAt(new \DateTime('now', new DateTimeZone('America/Sao_Paulo')));

            $em->persist($account);
            $em->flush();

            return new JsonResponse([
                'message' => 'Conta criada com sucesso',
                'data' => $this->serializer($account, 'json', SerializationContext::create()->setGroups('List'))
            ]);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }
}
