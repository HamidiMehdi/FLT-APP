<?php

namespace App\Repository;

use App\Entity\KnowHowAnnualInterview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method KnowHowAnnualInterview|null find($id, $lockMode = null, $lockVersion = null)
 * @method KnowHowAnnualInterview|null findOneBy(array $criteria, array $orderBy = null)
 * @method KnowHowAnnualInterview[]    findAll()
 * @method KnowHowAnnualInterview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KnowHowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KnowHowAnnualInterview::class);
    }

    // /**
    //  * @return KnowHowAnnualInterview[] Returns an array of KnowHowAnnualInterview objects
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
    public function findOneBySomeField($value): ?KnowHowAnnualInterview
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
