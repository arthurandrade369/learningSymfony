<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\Account;
use DateTimeZone;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

/**
 * @Route("/service/v1/oauth2", name="oauth2_")
 */
class Oauth2Controller extends AbstractController
{
    /**
     * @Route("", name="login")
     */
    public function login(Request $request)
    {
        $credentials = $request->request->all();

        $username = $credentials['username'];
        $password = $credentials['password'];

        $em = $this->getDoctrine()->getManager();
        $account = $em->getRepository(Account::class)->findOneBy($username);
        if($account['password'] === $password){
            return createToken();
        }

        return new Response('Invalid credentials', Response::HTTP_UNAUTHORIZED);
    }
}
