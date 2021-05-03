<?php

namespace App\Repository;

use App\Entity\KnowMakeAnnualInterview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method KnowMakeAnnualInterview|null find($id, $lockMode = null, $lockVersion = null)
 * @method KnowMakeAnnualInterview|null findOneBy(array $criteria, array $orderBy = null)
 * @method KnowMakeAnnualInterview[]    findAll()
 * @method KnowMakeAnnualInterview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KnowMakeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KnowMakeAnnualInterview::class);
    }

    // /**
    //  * @return KnowMakeAnnualInterview[] Returns an array of KnowMakeAnnualInterview objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?KnowMakeAnnualInterview
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
