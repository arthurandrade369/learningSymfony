<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Oauth2Request;
use App\Repository\AccountRepository;
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
        $em = $this->getDoctrine()->getManager();

        $oauth2 = $this->getObjectPerRequest($request, Oauth2Request::class);
        if (!$oauth2 instanceof Oauth2Request) {
            throw new Exception("Error Processing Request", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        switch ($oauth2->getGrantType()) {
            case Oauth2Request::GRANT_TYPE_PASSWORD:
                $repo = $em->getRepository(Account::class);
                if (!$repo instanceof AccountRepository) throw new Exception("Error Processing Entity", 500);
                $account = $repo->getOnlyOne($oauth2->getUsername());
                if ($account->getPassword() === $oauth2->getPassword()) {
                    return $this->createToken($request);
                }
                break;
            case Oauth2Request::GRANT_TYPE_REFRESH_TOKEN:
                # code...
                break;
            default:
                throw new Exception("Authentication method not recognized, grant type required", Response::HTTP_BAD_REQUEST);
                break;
        }

        return new Response('Invalid credentials', Response::HTTP_NOT_ACCEPTABLE);
    }

    public function refreshToken(Request $request)
    {
    }

    public function createToken(Request $request)
    {
        return 'Ta dando certo';
    }
}
