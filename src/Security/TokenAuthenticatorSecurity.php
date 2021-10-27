<?php

namespace App\Security;

use App\Controller\AbstractController;
use App\Provider\AccountUserProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticatorSecurity extends AbstractGuardAuthenticator
{
    private ?string $token;
    private ?string $tokenType;
    private AccountUserProvider $AccountUserProvider;

    /**
     * @param AccountUserProvider $AccountUserProvider
     */
    public function __construct(AccountUserProvider $AccountUserProvider)
    {
        $this->AccountUserProvider = $AccountUserProvider;
    }

    /**
     * @return AccountUserProvider|null
     */
    public function getAccountUserProvider(): ?AccountUserProvider
    {
        return $this->AccountUserProvider;
    }

    /**
     * @return array
     */
    public static function getAuthorizedIp(): array
    {
        return ['sim'];
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     * 
     * @param Request $request
     * @return boolean
     */
    public function supports(Request $request): bool
    {
        // if (in_array($request->getClientIp(), TokenAuthenticatorSecurity::getAuthorizedIp())) {
        //     return true;
        // }

        if ($request->headers->has('Authorization')) {
            $this->token = $request->headers->get('Authorization');
            $patternBearer = "/bearer .+/i";

            if (preg_match_all($patternBearer, $this->token)) {
                $this->tokenType = 'Bearer';
            } else {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     * 
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        $this->token = AbstractController::separateAuthorization($this->token);
        $token = AbstractController::separateToken($this->token);

        return array(
            'tokenId' => $token[0],
            'token' => $token[1],
            'tokenType' => $this->tokenType,
            'address' => $request->getClientIp()
        );
    }

    /**
     * @param $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $tokenId = $credentials['tokenId'];
        $token = $credentials['token'];
        $tokenType = $credentials['tokenType'];
        $address = $credentials['address'];

        if ($token === null && !in_array($address, TokenAuthenticatorSecurity::getAuthorizedIp())) {
            return null;
        }

        $tokenType = strtolower($tokenType);
        switch ($tokenType) {
            case 'bearer':
                return $this->getAccountUserProvider()->getByAccessToken($token, $tokenId, $address);
            default:
                return null;
        }
    }

    /**
     * @param $credentials
     * @param UserInterface $user
     * @return boolean
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param $providerKey
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        // on success, let the request continue
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Called when authentication is needed, but it's not sent
     * 
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $data = [
            // you might translate this message
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return boolean
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}
