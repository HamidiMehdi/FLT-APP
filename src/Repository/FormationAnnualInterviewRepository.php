<?php

namespace App\Repository;

use App\Entity\FormationAnnualInterview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FormationAnnualInterview|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormationAnnualInterview|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormationAnnualInterview[]    findAll()
 * @method FormationAnnualInterview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationAnnualInterviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormationAnnualInterview::class);
    }

    // /**
    //  * @return FormationAnnualInterview[] Returns an array of FormationAnnualInterview objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FormationAnnualInterview
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
