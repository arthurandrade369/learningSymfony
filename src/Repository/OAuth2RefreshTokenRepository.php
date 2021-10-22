<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\OAuth2RefreshToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMapping;
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

    public function getRefreshTokenByAccount(int $accountId)
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
                LIMIT 1';

            $query = $em->createNativeQuery($sql, $rsm);
            $query->setParameter('accountId', $accountId);

            return $query->getResult();
        } catch (NonUniqueResultException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    // /**
    //  * @return RefreshToken[] Returns an array of RefreshToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RefreshToken
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
