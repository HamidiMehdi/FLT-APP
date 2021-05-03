<?php

namespace App\Repository;

use App\Entity\GoalAnnualInterview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GoalAnnualInterview|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoalAnnualInterview|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoalAnnualInterview[]    findAll()
 * @method GoalAnnualInterview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoalAnnualInterviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoalAnnualInterview::class);
    }

    // /**
    //  * @return GoalAnnualInterview[] Returns an array of GoalAnnualInterview objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GoalAnnualInterview
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
