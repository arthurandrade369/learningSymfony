<?php

namespace App\Provider;

use App\Controller\AbstractController;
use App\Entity\Account;
use App\Entity\OAuth2AccessToken;
use App\Entity\OAuth2RefreshToken;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\OAuth2Request;
use App\Model\OAuth2Response;
use App\Repository\AccountRepository;
use App\Repository\OAuth2AccessTokenRepository;
use App\Repository\OAuth2RefreshTokenRepository;
use App\Security\TokenAuthenticatorSecurity;
use DateTime;
use DateTimeZone;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class AccountUserProvider implements UserProviderInterface
{
    private EntityManagerInterface $entityManager;
    private TokenGeneratorInterface $tokenGenerator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(EntityManagerInterface $entityManager, TokenGeneratorInterface $tokenGenerator)
    {
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return string
     */
    public function generateToken(): string
    {
        return $this->tokenGenerator->generateToken();
    }

    /**
     * @return UserInterface
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $repoAccount = $this->getEntityManager()->getRepository(Account::class);
        if (!$repoAccount instanceof AccountRepository) AbstractController::errorUnProcessableEntityResponse(Account::class);

        $account = $repoAccount->getAccountByEmail($username);
        if (!$account instanceof Account) throw new UsernameNotFoundException();

        return $account;
    }

    /**
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Account) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param $class
     * @return boolean
     */
    public function supportsClass($class): bool
    {
        return Account::class === $class || is_subclass_of($class, Account::class);
    }

    /**
     * @param Request $request
     * @param OAuth2Request $OAuth2Request
     * @return OAuth2Response
     * @throws Exception
     */
    public function createAccessTokenByPassword(Request $request, OAuth2Request $OAuth2Request)
    {
        $repoUser = $this->entityManager->getRepository(Account::class);

        if (empty($OAuth2Request->getUsername()) || empty($OAuth2Request->getPassword())) {
            AbstractController::errorUnProcessableEntityResponse("Username and password is required");
        }

        // checks if the user exists
        $account = $repoUser->findOneBy([
            'email' => $OAuth2Request->getUsername(),
            'password' => md5($OAuth2Request->getPassword())
        ]);
        if (!$account instanceof Account) AbstractController::errorInternalServerResponse(Account::class);
        if (!$account->getEnabled()) throw new Exception('Trying to access a disabled account', Response::HTTP_BAD_REQUEST);

        $refreshToken = $this->createRefreshToken($account, $request);
        if (!$refreshToken instanceof OAuth2RefreshToken) AbstractController::errorInternalServerResponse(OAuth2RefreshToken::class);

        return $this->createAccessToken($request, $refreshToken, $account);
    }

    /**
     * @param Request $request
     * @param OAuth2Request $oauth2Request
     * @return OAuth2Response
     */
    public function createAccessTokenByRefreshToken(Request $request, OAuth2Request $oauth2Request): OAuth2Response
    {
        $mToken = explode('_', $oauth2Request->getRefreshToken(), 2);
        $tokenId = $mToken[0] ?? null;
        $token = $mToken[1] ?? null;

        $em = $this->getEntityManager();
        $repoRefreshToken = $em->getRepository(OAuth2RefreshToken::class);
        if (!$repoRefreshToken instanceof OAuth2RefreshTokenRepository) AbstractController::errorInternalServerResponse(OAuth2RefreshTokenRepository::class);

        $refreshToken = $repoRefreshToken->getRefreshToken($token, $tokenId);

        if ($refreshToken) {
            $refreshToken = $this->updateRefreshToken($refreshToken, $refreshToken->getAccount());
        }

        if (!$refreshToken) {
            throw new Exception("Refresh Token Invalid", Response::HTTP_UNAUTHORIZED);
        }

        if (!$refreshToken instanceof OAuth2RefreshToken) {
            AbstractController::errorInternalServerResponse(OAuth2RefreshToken::class);
        }

        return $this->createAccessToken($request, $refreshToken, $refreshToken->getAccount());
    }

    /**
     * @param Account $account
     * @return OAuth2RefreshToken
     */
    public function createRefreshToken(Account $account, Request $request): OAuth2RefreshToken
    {
        $em = $this->getEntityManager();
        $repoRefreshToken = $em->getRepository(OAuth2RefreshToken::class);
        if (!$repoRefreshToken instanceof OAuth2RefreshTokenRepository) {
            throw new Exception('Error Processing Repository', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $refreshToken = $repoRefreshToken->getRefreshTokenByAccount($account->getId(), $request->getClientIp());
        if ($refreshToken) return $this->updateRefreshToken($refreshToken, $account);

        $refreshToken = new OAuth2RefreshToken();
        $refreshToken->setRefreshToken($this->generateToken());
        $refreshToken->setAccount($account);
        $em->persist($refreshToken);
        $em->flush();

        return $refreshToken;
    }

    /**
     * @param Request $request
     * @param OAuth2RefreshToken $refreshToken
     * @param Account $account
     * @return OAuth2Response
     */
    public function createAccessToken(Request $request, OAuth2RefreshToken $refreshToken, Account $account): OAuth2Response
    {
        $em = $this->getEntityManager();

        $repoAccessToken = $em->getRepository(OAuth2AccessToken::class);
        if (!$repoAccessToken instanceof OAuth2AccessTokenRepository) throw new Exception('Error Processing Repository', Response::HTTP_INTERNAL_SERVER_ERROR);

        $accessToken = $repoAccessToken->getAccessToken($refreshToken->getId(), $refreshToken->getRefreshToken(), $request->getClientIp());
        if ($accessToken) return $this->updateAccessToken($request, $accessToken, $account);

        $accessToken = new OAuth2AccessToken();
        $accessToken->setAccessToken($this->generateToken());
        $accessToken->setExpirationAt(new DateTime('+' . OAuth2RefreshToken::TTL . 'seconds', new DateTimeZone('America/Sao_Paulo')));
        $accessToken->setTokenType(OAuth2Response::TOKEN_TYPE_BEARER);
        $accessToken->setRefreshToken($refreshToken);
        $accessToken->setAddress($request->getClientIp());

        $em->persist($accessToken);
        $em->flush();

        return $this->generateResponseOauth2($accessToken, $account);
    }

    /**
     * @param string $token
     * @param string $tokenId
     * @param string $address
     * @return object|null
     */
    public function getByAccessToken($token, $tokenId, $address)
    {
        $em = $this->getEntityManager();

        $repoAccessToken = $em->getRepository(Account::class);
        if (!$repoAccessToken instanceof AccountRepository) return null;

        $account = $repoAccessToken->getUserByAccessToken($token, $tokenId);
        if ($account instanceof Account) {
            return $account;
        }
        if (!in_array($address, TokenAuthenticatorSecurity::getAuthorizedIp())) {
            return null;
        }

        return $em->getRepository(Account::class)->find(1);
    }

    /**
     * @param OAuth2RefreshToken $refreshToken
     * @param Account $account
     * @return OAuth2RefreshToken
     */
    public function updateRefreshToken(OAuth2RefreshToken $refreshToken, Account $account): OAuth2RefreshToken
    {
        $em = $this->getEntityManager();

        $refreshToken->setRefreshToken($this->generateToken());
        $refreshToken->setAccount($account);

        $em->persist($refreshToken);
        $em->flush();

        return $refreshToken;
    }

    /**
     * @param Request $request
     * @param OAuth2AccessToken $accessToken
     * @param Account $account
     * @return OAuth2Response
     */
    public function updateAccessToken(Request $request, OAuth2AccessToken $accessToken, Account $account): OAuth2Response
    {
        $em = $this->getEntityManager();

        $accessToken->setAccessToken($this->generateToken());
        $accessToken->setAddress($request->getClientIp());
        $accessToken->setExpirationAt((new DateTime(sprintf('+ %d seconds', OAuth2RefreshToken::TTL), new DateTimeZone('America/Sao_Paulo'))));

        $em->persist($accessToken);
        $em->flush();

        return $this->generateResponseOauth2($accessToken, $account);
    }

    /**
     * @param OAuth2AccessToken $accessToken
     * @param Account $account
     * @return OAuth2Response
     */
    public function generateResponseOauth2(OAuth2AccessToken $accessToken, Account $account): OAuth2Response
    {
        $oauth2Response = new OAuth2Response();
        $oauth2Response->setRefreshToken($accessToken->getRefreshToken()->getId() . '_' . $accessToken->getRefreshToken()->getRefreshToken());
        $oauth2Response->setAccessToken($accessToken->getId() . '_' . $accessToken->getAccessToken());
        $oauth2Response->setAddress($accessToken->getAddress());
        $oauth2Response->setTokenType($accessToken->getTokenType());
        $oauth2Response->setExpiresIn(OAuth2RefreshToken::TTL);
        $oauth2Response->setScope($account->getScope());

        return $oauth2Response;
    }
}
