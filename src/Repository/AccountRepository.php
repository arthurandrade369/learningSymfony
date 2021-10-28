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

    /**
     * @param string $email
     * @return mixed
     */
    public function getAccountByEmail(string $email)
    {
        try {
            $rsm = new ResultSetMappingBuilder($this->getEntityManager());
            $rsm->addRootEntityFromClassMetadata(Account::class, 'a');
            $sql = '
                SELECT
                    a.*
                FROM
                    accounts AS a
                WHERE 
                    a.email = :email
                LIMIT 1';

            $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
            $query->setParameter('email', $email);

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
    public function getUserByAccessToken(string $token, string $tokenId)
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
                    INNER JOIN oauth2_access_token AS oat ON oat.refresh_token_id = oat.id AND oat.id = :tokenId
                WHERE
                    oat.access_token = :token AND a.enabled = :enabled AND oat.expiration_at > NOW()
                LIMIT 1';

            $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
            $query->setParameter('tokenId', $tokenId);
            $query->setParameter('token', $token);
            $query->setParameter('enabled', true);

            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
}
