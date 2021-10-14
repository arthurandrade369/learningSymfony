<?php

namespace App\Repository;

use App\Entity\OAuth2RefreshToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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

    public function getRefreshTokenByAccount($accountId)
    {
        try {
            $rsm = new ResultSetMappingBuilder($this->getEntityManager());
            $rsm->addRootEntityFromClassMetadata(OAuth2RefreshToken::class,'rtk');
            $sql = '
                SELECT
                    rtk.*
                FROM
                    oauth2_refresh_token AS rtk
                    INNER JOIN accounts AS a ON rtk.account_id = :id
                LIMIT 1';

            $query = $this->getEntityManager()->createNativeQuery($sql,$rsm);
            $query->setParameter('id',$accountId);
            
            return $query->getResult();
        } catch (Exception $exception) {
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
