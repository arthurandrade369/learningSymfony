<?php

namespace App\Repository;

use App\Entity\OAuth2RefreshToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefreshToken[]    findAll()
 * @method RefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OAuth2RefreshTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OAuth2RefreshToken::class);
    }

    /**
     * @param integer $accountId
     * @param string $address
     * @return mixed    
     */
    public function getRefreshTokenByAccount(int $accountId, string $address)
    {
        try {
            $em = $this->getEntityManager();
            $rsm = new ResultSetMappingBuilder($em);
            $rsm->addRootEntityFromClassMetadata(OAuth2RefreshToken::class, 'ort');
            $sql = '
                SELECT
                    ort.*
                FROM
                    oauth2_refresh_token AS ort
                    INNER JOIN accounts AS a ON ort.account_id = :accountId
                    INNER JOIN oauth2_access_token AS oat ON oat.address = :address
                LIMIT 1';

            $query = $em->createNativeQuery($sql, $rsm);
            $query->setParameter('accountId', $accountId);
            $query->setParameter('address', $address);

            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }

    /**
     * @param string $token
     * @param string $tokenId
     * @param string $address
     * @return mixed
     */
    public function getRefreshTokenByAccessToken(string $token, string $tokenId, string $address)
    {
        try {
            $em = $this->getEntityManager();
            $rsm = new ResultSetMappingBuilder($em);
            $rsm->addRootEntityFromClassMetadata(OAuth2RefreshToken::class, 'ort');
            $sql = '
                SELECT
                    ort.*
                FROM
                    oauth2_refresh_token AS ort
                    INNER JOIN oauth2_access_token AS oat ON oat.id = :id AND oat.access_token = :token
                WHERE
                    oat.address = :address
                LIMIT 1';

            $query = $em->createNativeQuery($sql, $rsm);
            $query->setParameter('id', $tokenId);
            $query->setParameter('token', $token);
            $query->setParameter('address', $address);

            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }

    /**
     * @param string $token
     * @param string $tokenId
     * @return mixed
     */
    public function getRefreshToken(string $token, string $tokenId)
    {
        try {
            $em = $this->getEntityManager();
            $rsm = new ResultSetMappingBuilder($em);
            $rsm->addRootEntityFromClassMetadata(OAuth2RefreshToken::class, 'ort');
            $sql = '
                SELECT
                    ort.*
                FROM
                    oauth2_refresh_token AS ort
                WHERE
                    ort.id = :id AND ort.refresh_token = :token
                LIMIT 1';

            $query = $em->createNativeQuery($sql, $rsm);
            $query->setParameter('id', $tokenId);
            $query->setParameter('token', $token);

            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
}
