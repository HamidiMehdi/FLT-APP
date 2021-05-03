<?php

namespace App\Repository;

use App\Entity\AnnualInterview;
use App\Entity\User;
use App\Enum\PaginationEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method AnnualInterview|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnualInterview|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnualInterview[]    findAll()
 * @method AnnualInterview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnualInterviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnualInterview::class);
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCurrentAnnualInterviewByUser(User $user)
    {
        $currentYear = date('Y');
        $dql = "
            SELECT ai
            FROM App\Entity\AnnualInterview ai
            WHERE ai.employee = :user
            AND YEAR(ai.created_at) = :currentYear
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'user' => $user,
            'currentYear' => $currentYear
        ]);

        return $query->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @param $offset
     * @param $search
     * @return Paginator
     */
    public function getAnnualInterviewByManager(User $user, $offset, $search)
    {
        $usersManaged = $this->_em->getRepository(User::class)->getEmployeeIdManagedByUser($user);

        $dql = "
            SELECT ai
            FROM App\Entity\AnnualInterview ai
            JOIN ai.employee u
            WHERE ai.employee IN (:users_managed)
            AND ai.ownInterviewValidated IS NOT NULL
            AND ai.interviewValidated IS NULL
        ";

        $parameters = [
            'users_managed' => $usersManaged
        ];

        if ($search != null) {
            $dql .= " AND (LOWER(u.lastName) like :search or LOWER(u.firstName) like :search) ";
            $parameters['search'] = '%' . strtolower(addslashes($search)) . '%';
        }
        $dql .= "ORDER BY ai.created_at DESC";

        $query = $this->_em->createQuery($dql);
        $query->setParameters($parameters);
        $query->setFirstResult($offset);
        $query->setMaxResults(PaginationEnum::DEFAULT_NUMBER_ELEMENT);

        return new Paginator($query);
    }

    /**
     * @param User $user
     * @param $offset
     * @param $search
     * @return Paginator
     */
    public function getAnnualInterviewByUser(User $user, $offset, $search)
    {
        $dql = "
            SELECT ai
            FROM App\Entity\AnnualInterview ai
            JOIN ai.employee u
            WHERE ai.employee = :user
            AND ai.interviewValidated IS NOT NULL
        ";

        $parameters = [
            'user' => $user
        ];

        if ($search != null) {
            $dql .= " AND (LOWER(u.lastName) like :search or LOWER(u.firstName) like :search) ";
            $parameters['search'] = '%' . strtolower(addslashes($search)) . '%';
        }
        $dql .= "ORDER BY ai.created_at DESC";

        $query = $this->_em->createQuery($dql);
        $query->setParameters($parameters);
        $query->setFirstResult($offset);
        $query->setMaxResults(PaginationEnum::DEFAULT_NUMBER_ELEMENT);

        return new Paginator($query);
    }

    /**
     * @param User $user
     * @param $offset
     * @param $search
     * @return Paginator
     */
    public function getAnnualInterviewValidatedByManager(User $user, $offset, $search)
    {
        $usersManaged = $this->_em->getRepository(User::class)->getEmployeeIdManagedByUser($user);

        $dql = "
            SELECT ai
            FROM App\Entity\AnnualInterview ai
            JOIN ai.employee u
            WHERE ai.employee IN (:users_managed)
            AND ai.ownInterviewValidated IS NOT NULL
            AND ai.interviewValidated IS NOT NULL
        ";

        $parameters = [
            'users_managed' => $usersManaged
        ];

        if ($search != null) {
            $dql .= " AND (LOWER(u.lastName) like :search or LOWER(u.firstName) like :search) ";
            $parameters['search'] = '%' . strtolower(addslashes($search)) . '%';
        }
        $dql .= "ORDER BY ai.created_at DESC";

        $query = $this->_em->createQuery($dql);
        $query->setParameters($parameters);
        $query->setFirstResult($offset);
        $query->setMaxResults(PaginationEnum::DEFAULT_NUMBER_ELEMENT);

        return new Paginator($query);
    }

    /**
     * @param AnnualInterview $ai
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPrevAnnualInterview(AnnualInterview $ai)
    {
        $dql = "
            SELECT ai
            FROM App\Entity\AnnualInterview ai
            WHERE YEAR(ai.created_at) = (
                SELECT MAX(YEAR(ain.created_at))
                FROM App\Entity\AnnualInterview ain
                WHERE ai.employee = :employee
                AND YEAR(ain.created_at) < :yearAi
                AND ain.interviewValidated IS NOT NULL
            )
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'employee' => $ai->getEmployee(),
            'yearAi' => $ai->getCreatedAt()->format('Y')
        ]);

        return $query->getOneOrNullResult();
    }

    /**
     * @param $year
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAvgBilanByYear($year)
    {
        $dql = "
            SELECT ROUND(AVG(ai.bilanAvg),2) as avg
            FROM App\Entity\AnnualInterview ai
            WHERE YEAR(ai.created_at) = :year
            AND ai.ownInterviewValidated IS NOT NULL
            AND ai.interviewValidated IS NOT NULL
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('year', $year);

        return $query->getOneOrNullResult();
    }

    /**
     * @param $year
     * @return mixed
     */
    public function getAnnualInterviewAvgByYear($year)
    {
        $dql = "
            SELECT COUNT(ai.id) as counter, ai.bilanAvg as avg
            FROM App\Entity\AnnualInterview ai
            WHERE YEAR(ai.created_at) = :year
            AND ai.ownInterviewValidated IS NOT NULL
            AND ai.interviewValidated IS NOT NULL
            GROUP BY ai.bilanAvg
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('year', $year);

        $result = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0
        ];

        foreach ($query->getResult() as $item) {
            if (array_key_exists($item['avg'], $result)) {
                $result[$item['avg']] = $result[$item['avg']] + $item['counter'];
            }
        }

        return $result;
    }
}
