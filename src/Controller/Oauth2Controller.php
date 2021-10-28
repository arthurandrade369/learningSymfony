<?php

namespace App\Controller;

use App\Controller\AbstractCrudController;
use App\Entity\OAuth2RefreshToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\OAuth2Request;
use Exception;

class OAuth2Controller extends AbstractCrudController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        try {
            $oauth2Request = $this->getObjectPerRequest($request, OAuth2Request::class);
            if (!$oauth2Request instanceof OAuth2Request) AbstractController::errorInternalServerResponse(OAuth2Request::class);

            switch ($oauth2Request->getGrantType()) {
                case OAuth2Request::GRANT_TYPE_PASSWORD:
                    $oauth2Response = $this->getAccountUserProvider()->createAccessTokenByPassword($request, $oauth2Request);
                    break;
                case OAuth2Request::GRANT_TYPE_REFRESH_TOKEN:
                    $oauth2Response = $this->getAccountUserProvider()->createAccessTokenByRefreshToken($request, $oauth2Request);
                    break;
                default:
                    throw new Exception("Authentication method not recognized, grant type required", Response::HTTP_BAD_REQUEST);
                    break;
            }

            return $this->showResponse($request, $oauth2Response);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request): Response
    {
        try {
            $oauth2Request = $this->getObjectPerRequest($request, OAuth2Request::class);
            if(!$oauth2Request instanceof OAuth2Request) AbstractController::errorInternalServerResponse(OAuth2Request::class);

            $mToken = AbstractController::separateToken($oauth2Request->getRefreshToken());
            $tokenId = $mToken[0];
            
            return $this->delete($tokenId, OAuth2RefreshToken::class, $request);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function me(Request $request): Response
    {
        try {
            $user = $this->getUser();

            return $this->showResponse($request, $user);
        } catch (Exception $exception) {
            return $this->exceptionResponse($request, $exception);
        }
    }
}
