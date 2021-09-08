<?php

namespace App\Repository;

use App\Entity\PublishingCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublishingCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublishingCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublishingCompany[]    findAll()
 * @method PublishingCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublishingCompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublishingCompany::class);
    }

    // /**
    //  * @return PublishingCompany[] Returns an array of PublishingCompany objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PublishingCompany
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
