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
use App\Repository\AccountRepository;
use App\Repository\OAuth2AccessTokenRepository;
use App\Security\TokenAuthenticatorSecurity;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class AccountUserProvider implements UserProviderInterface
{
    private EntityManagerInterface $entityManager;
    private TokenGeneratorInterface $tokenGenerator;

    public function __construct(EntityManagerInterface $entityManager, TokenGeneratorInterface $tokenGenerator)
    {
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
    }

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
        return 'Ta chegando aqui';
        throw new Exception('TODO: fill in loadUserByUsername() inside ' . __FILE__);
    }

    /**
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Account) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        throw new Exception('TODO: fill in refreshUser() inside ' . __FILE__);
    }

    public function supportsClass($class)
    {
        return Account::class === $class || is_subclass_of($class, Account::class);
    }

    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
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

        $refreshToken = $this->createRefreshToken($account);
        if (!$refreshToken instanceof OAuth2RefreshToken) {
            throw new Exception("Refresh token error", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->createAccessToken($request, $refreshToken);
    }

    public function createAccessToken(Request $request, OAuth2RefreshToken $refreshToken)
    {
        $token = new OAuth2AccessToken();
        $token->setAccessToken($this->generateToken());
        $token->setExpirationAt(new \DateTime('+1 hour', new \DateTimeZone('America/Sao_Paulo')));
        $token->setTypeToken('Bearer');
        $token->setRefreshToken($refreshToken);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $token;
    }

    public function createRefreshToken(Account $account)
    {
        $refreshToken = new OAuth2RefreshToken();
        $refreshToken->setRefreshToken($this->generateToken());
        $refreshToken->setExpirationAt(new \DateTime('+1 hour', new \DateTimeZone('America/Sao_Paulo')));
        $refreshToken->setAccount($account);

        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();

        return $refreshToken;
    }

    public function getByAccessToken($token, $tokenId, $address)
    {
        $repoAccessToken = $this->entityManager->getRepository(Account::class);
        if (!$repoAccessToken instanceof AccountRepository) return null;

        $account = $repoAccessToken->getUserByAccessToken($token, $tokenId);
        if ($account instanceof Account) {
            return $account;
        }
        if (!in_array($address, TokenAuthenticatorSecurity::getAuthorizedIp())) {
            return null;
        }

        return $this->entityManager->getRepository(Account::class)->find(1);
    }
}
