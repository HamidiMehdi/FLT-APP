<?php

namespace App\Repository;

use App\Entity\InterviewProP1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InterviewProP1|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterviewProP1|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterviewProP1[]    findAll()
 * @method InterviewProP1[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterviewProP1Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InterviewProP1::class);
    }

    // /**
    //  * @return InterviewProP1[] Returns an array of InterviewProP1 objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InterviewProP1
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
