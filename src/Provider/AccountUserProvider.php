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

    /**
     * @param Account $account
     * @return OAuth2RefreshToken
     */
    public function createRefreshToken(Account $account): OAuth2RefreshToken
    {
        $em = $this->getEntityManager();

        $repoRefreshToken = $em->getRepository(OAuth2RefreshToken::class);
        if(!$repoRefreshToken instanceof OAuth2RefreshTokenRepository) throw new Exception('Error Processing Repository', Response::HTTP_INTERNAL_SERVER_ERROR);
        $refreshToken = $repoRefreshToken->getRefreshTokenByAccount($account->getId());
        if($refreshToken) return $this->updateRefreshToken($refreshToken,$account);

        $refreshToken = new OAuth2RefreshToken();
        $refreshToken->setRefreshToken($this->generateToken());
        $refreshToken->setAccount($account);
        $em->persist($refreshToken);
        $em->flush();

        return $refreshToken;
    }
    
    public function createAccessToken(Request $request, OAuth2RefreshToken $refreshToken): OAuth2Response
    {
        $em = $this->getEntityManager();
        
        $repoAccessToken = $em->getRepository(OAuth2AccessToken::class);
        if(!$repoAccessToken instanceof OAuth2AccessTokenRepository) throw new Exception('Error Processing Repository', Response::HTTP_INTERNAL_SERVER_ERROR);
        $accessToken = $repoAccessToken->getAccessToken($refreshToken->getId(),$refreshToken->getRefreshToken());


        $acessToken = new OAuth2AccessToken();
        $acessToken->setAccessToken($this->generateToken());
        $acessToken->setExpirationAt(new \DateTime('+1 hour', new \DateTimeZone('America/Sao_Paulo')));
        $acessToken->setTypeToken('Bearer');
        $acessToken->setRefreshToken($refreshToken);

        $this->entityManager->persist($acessToken);
        $this->entityManager->flush();

        return $acessToken;
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
