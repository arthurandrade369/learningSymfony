<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\PublishingCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Boo,k[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    public function findAllWithJoin()
    {
        try {
            // $sql = "
            //     SELECT
            //         b.id, b.title, b.author, b.quantity_pages, b.release_date, p.name AS company
            //     FROM
            //         book AS b
            //         INNER JOIN publishing_company AS p ON p.id = b.publishing_company_id
            // ";

            // $rsm = new ResultSetMapping();
            // $rsm->addScalarResult('id', 'id');
            // $rsm->addScalarResult('title', 'title');
            // $rsm->addScalarResult('author', 'author');
            // $rsm->addScalarResult('quantity_pages', 'quantityPages');
            // $rsm->addScalarResult('release_date', 'releaseDate');
            // $rsm->addScalarResult('company', 'company');
            
            // $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

            // return $query->getResult();

            return $this->createQueryBuilder('b')
            ->innerJoin('publishingCompany','p','WITH','p.id = b.publishingCompany')
            ->getQuery()->getResult();
            
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
    
    public function findOnlyOne(int $id)
    {
        try {
            $sql = "
                SELECT
                    b.id, b.title, b.author, b.quantity_pages, b.release_date, p.name
                FROM
                    book AS b
                    INNER JOIN publishing_company AS p ON p.id = b.publishing_company_id AND b.id = :id
            ";

            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('id', 'id');
            $rsm->addScalarResult('title', 'title');
            $rsm->addScalarResult('author', 'author');
            $rsm->addScalarResult('quantity_pages', 'quantityPages');
            $rsm->addScalarResult('release_date', 'releaseDate');
            $rsm->addScalarResult('name', 'name');


            $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
            $query->setParameter('id', $id);

            return $query->getResult();

        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
