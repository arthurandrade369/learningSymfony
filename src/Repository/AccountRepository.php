<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function getByEmail($email)
    {
        try {
            return $this->createQueryBuilder('a')
                ->where('a.email = :email')
                ->setParameter('email', $email)
                ->getQuery()->getResult();
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }

    public function getOnlyOne($email)
    {
        try {
            $sql = "
            SELECT
                a.*
            FROM
                accounts AS a
            WHERE
                a.email = :email
            LIMIT 1";

            $rsm = new ResultSetMappingBuilder($this->getEntityManager());
            $rsm->addRootEntityFromClassMetadata(Account::class, 'a');
            $rsm->addScalarResult('email', 'email');
            $rsm->addScalarResult('password', 'password');
            $rsm->addScalarResult('password', 'password');

            $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
            $query->setParameter('email', $email);

            return $query->getResult();
        } catch (NonUniqueResultException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }

    public function getUserByAccessToken($token, $tokenId)
    {
        try {
            $rsm = new ResultSetMappingBuilder($this->getEntityManager());
            $rsm->addRootEntityFromClassMetadata(Account::class, 'a');
            $sql = '
                SELECT
                    a.*
                FROM
                    accounts AS a
                    INNER JOIN oauth2_refresh_token AS ort ON a.id = ort.account_id
                    INNER JOIN oauth2_access_token AS oat ON oat.refresh_token_id = rt.id AND oat.id = :id
                WHERE
                    oat.access_token = :token AND a.enabled = :enabled AND oat.expiration_at > NOW() 
                LIMIT 1';

            $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
            $query->setParameter('id', $tokenId);
            $query->setParameter('token', $token);
            $query->setParameter('enabled', true);

            return $query->getResult();
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }

    // /**
    //  * @return Account[] Returns an array of Account objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Account
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
