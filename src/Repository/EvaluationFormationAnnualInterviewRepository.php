<?php

namespace App\Repository;

use App\Entity\EvaluationFormationAnnualInterview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EvaluationFormationAnnualInterview|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvaluationFormationAnnualInterview|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvaluationFormationAnnualInterview[]    findAll()
 * @method EvaluationFormationAnnualInterview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationFormationAnnualInterviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvaluationFormationAnnualInterview::class);
    }

    // /**
    //  * @return EvaluationFormationAnnualInterview[] Returns an array of EvaluationFormationAnnualInterview objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EvaluationFormationAnnualInterview
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
