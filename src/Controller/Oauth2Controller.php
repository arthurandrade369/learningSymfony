<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\OAuth2Request;
use App\Repository\AccountRepository;
use Exception;
use App\Provider\AccountProvider;

/**
 * @Route("/service/v1/oauth2", name="oauth2_")
 */
class OAuth2Controller extends AbstractController
{
    /**
     * @Route("", name="login")
     */
    public function login(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $OAuth2Request = $this->getObjectPerRequest($request, OAuth2Request::class);
        if (!$OAuth2Request instanceof OAuth2Request) {
            throw new Exception("Error Processing Request", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        switch ($OAuth2Request->getGrantType()) {
            case OAuth2Request::GRANT_TYPE_PASSWORD:

                $token = $this->getAccountProvider()->createAccessTokenByPassword($request, $OAuth2Request);

                break;
            case OAuth2Request::GRANT_TYPE_REFRESH_TOKEN:
                # code...
                break;
            default:
                throw new Exception("Authentication method not recognized, grant type required", Response::HTTP_BAD_REQUEST);
                break;
        }

        return $this->abstractResponse($token);
    }
}
