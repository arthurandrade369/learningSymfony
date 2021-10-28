<?php

namespace App\Repository;

use App\Entity\OAuth2AccessToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AcessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AcessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AcessToken[]    findAll()
 * @method AcessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OAuth2AccessTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OAuth2AccessToken::class);
    }

    /**
     * @param string $refreshTokenId
     * @param string $refreshToken
     * @param string $address
     * @return mixed
     */
    public function getAccessToken(string $refreshTokenId, string $refreshToken, string $address)
    {
        try {
            $em = $this->getEntityManager();

            $rsm = new ResultSetMappingBuilder($em);
            $rsm->addRootEntityFromClassMetadata(OAuth2AccessToken::class, 'oat');
            $sql = '
                SELECT
                    oat.*
                FROM
                    oauth2_access_token AS oat
                    INNER JOIN oauth2_refresh_token AS ort ON oat.refresh_token_id = :refreshTokenId AND ort.refresh_token = :refreshToken
                WHERE
                    oat.address = :address
                LIMIT 1 ';

            $query = $em->createNativeQuery($sql, $rsm);
            $query->setParameter('refreshTokenId', $refreshTokenId);
            $query->setParameter('refreshToken', $refreshToken);
            $query->setParameter('address', $address);

            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
}
