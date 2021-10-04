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
use App\Provider\AccountProvider;

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

        $oauth2Request = $this->getObjectPerRequest($request, Oauth2Request::class);
        if (!$oauth2Request instanceof Oauth2Request) {
            throw new Exception("Error Processing Request", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        switch ($oauth2Request->getGrantType()) {
            case Oauth2Request::GRANT_TYPE_PASSWORD:

                $token = $this->getAccountProvider()->createAccessTokenByPassword($request, $oauth2Request);

                break;
            case Oauth2Request::GRANT_TYPE_REFRESH_TOKEN:
                # code...
                break;
            default:
                throw new Exception("Authentication method not recognized, grant type required", Response::HTTP_BAD_REQUEST);
                break;
        }

        return $this->abstractResponse($token);
    }

    public function createRefreshToken(Request $request)
    {
    }

    public function createAccessToken(Request $request, Account $account)
    {
        return new Response('Ta dando certo fml');
    }
}
