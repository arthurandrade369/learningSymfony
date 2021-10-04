<?php

namespace App\Provider;

use App\Entity\Account;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Oauth2Request;
use Symfony\Component\Security\Guard\Token\PreAuthenticationGuardToken;

class AccountProvider implements UserProviderInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        // Load a User object from your data source or throw UsernameNotFoundException.
        // The $username argument may not actually be a username:
        // it is whatever value is being returned by the getUsername()
        // method in your User class.
        throw new Exception('TODO: fill in loadUserByUsername() inside ' . __FILE__);
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Account) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
        throw new Exception('TODO: fill in refreshUser() inside ' . __FILE__);
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class)
    {
        return Account::class === $class || is_subclass_of($class, Account::class);
    }

    /**
     * Upgrades the encoded password of a user, typically for using a better hash algorithm.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        // TODO: when encoded passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newEncodedPassword);
    }

    /**
     * @param Request $request
     * @param OAuth2Request $oauth2Request
     * @return OAuth2Response
     * @throws Exception
     */
    public function createAccessTokenByPassword(Request $request, OAuth2Request $oauth2Request)
    {
        $repoUser = $this->entityManager->getRepository(Account::class);

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

    public function createAccessToken(Request $request, Account $account)
    {
        $token = random_bytes(10);
        return $token;
    }
}
