<?php

namespace App\Repository;

use App\Entity\BilanAnnualInterview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BilanAnnualInterview|null find($id, $lockMode = null, $lockVersion = null)
 * @method BilanAnnualInterview|null findOneBy(array $criteria, array $orderBy = null)
 * @method BilanAnnualInterview[]    findAll()
 * @method BilanAnnualInterview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BilanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BilanAnnualInterview::class);
    }

    // /**
    //  * @return BilanAnnualInterview[] Returns an array of BilanAnnualInterview objects
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
    public function findOneBySomeField($value): ?BilanAnnualInterview
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
