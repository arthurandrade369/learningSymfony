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

        $oauth2Request = $this->getObjectPerRequest($request, Oauth2Request::class);
        if (!$oauth2Request instanceof Oauth2Request) {
            throw new Exception("Error Processing Request", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        switch ($oauth2Request->getGrantType()) {
            case Oauth2Request::GRANT_TYPE_PASSWORD:

                // $repo = $em->getRepository(Account::class);

                // $account = $repo->findOneBy([
                //     'email' => $oauth2Request->getUsername(),
                //     'password' => $oauth2Request->getPassword()
                // ]);

                $this->createAccessTokenByPassword($request, $oauth2Request);

                break;
            case Oauth2Request::GRANT_TYPE_REFRESH_TOKEN:
                # code...
                break;
            default:
                throw new Exception("Authentication method not recognized, grant type required", Response::HTTP_BAD_REQUEST);
                break;
        }

        return $this->createAccessTokenByPassword($request, $oauth2Request);
    }

    /**
     * @param Request $request
     * @param OAuth2Request $oauth2Request
     * @return OAuth2Response
     * @throws Exception
     */
    public function createAccessTokenByPassword(Request $request, OAuth2Request $oauth2Request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoUser = $em->getRepository(Account::class);

        // if (empty($oauth2Request->getUsername()) || empty($oauth2Request->getPassword())) {
        //     AbstractController::errorUnProcessableEntityResponse("username and password is required");
        // }

        // checks if the user exists
        $account = $repoUser->findOneBy([
            'email' => $oauth2Request->getUsername(),
            'password' => $oauth2Request->getPassword()
        ]);
        if (!$account) throw new Exception('Username or password are invalid', Response::HTTP_UNAUTHORIZED);
        // if (!$account instanceof Account) AbstractController::errorInternalServerResponse(Account::class);

        // $refreshToken = $this->createRefreshToken($account);
        // if (!$refreshToken instanceof OAuth2RefreshToken) {
        //     throw new Exception("Refresh token error", Response::HTTP_INTERNAL_SERVER_ERROR);
        // }

        return $this->createAccessToken($request, $account);
    }

    public function createRefreshToken(Request $request)
    {
    }

    public function createAccessToken(Request $request, Account $account)
    {
        return new Response('Ta dando certo fml');
    }
}
