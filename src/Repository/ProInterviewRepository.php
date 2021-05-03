<?php

namespace App\Repository;

use App\Entity\ProInterview;
use App\Entity\User;
use App\Enum\PaginationEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method ProInterview|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProInterview|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProInterview[]    findAll()
 * @method ProInterview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProInterviewRepository extends ServiceEntityRepository
{
    /**
     * ProInterviewRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProInterview::class);
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCurrentProInterviewByUser(User $user)
    {
        $currentYear = date('Y');
        $dql = "
            SELECT pi
            FROM App\Entity\ProInterview pi
            WHERE pi.employee = :user
            AND YEAR(pi.created_at) = :currentYear
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
    public function getProInterviewByManager(User $user, $offset, $search)
    {
        $usersManaged = $this->_em->getRepository(User::class)->getEmployeeIdManagedByUser($user);

        $dql = "
            SELECT pi
            FROM App\Entity\ProInterview pi
            JOIN pi.employee u
            WHERE pi.employee IN (:users_managed)
            AND pi.ownInterviewValidated IS NOT NULL
            AND pi.interviewValidated IS NULL
        ";

        $parameters = [
            'users_managed' => $usersManaged,
        ];

        if ($search != null) {
            $dql .= " AND (LOWER(u.lastName) like :search or LOWER(u.firstName) like :search) ";
            $parameters['search'] = '%' . strtolower(addslashes($search)) . '%';
        }
        $dql .= "ORDER BY pi.created_at DESC";

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
    public function getProInterviewByUser(User $user, $offset, $search)
    {
        $dql = "
            SELECT pi
            FROM App\Entity\ProInterview pi
            JOIN pi.employee u
            WHERE pi.employee = :user
            AND pi.interviewValidated IS NOT NULL
        ";

        $parameters = [
            'user' => $user,
        ];

        if ($search != null) {
            $dql .= " AND (LOWER(u.lastName) like :search or LOWER(u.firstName) like :search) ";
            $parameters['search'] = '%' . strtolower(addslashes($search)) . '%';
        }
        $dql .= "ORDER BY pi.created_at DESC";

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
    public function getProInterviewValidatedByManager(User $user, $offset, $search)
    {
        $usersManaged = $this->_em->getRepository(User::class)->getEmployeeIdManagedByUser($user);

        $dql = "
            SELECT pi
            FROM App\Entity\ProInterview pi
            JOIN pi.employee u
            WHERE pi.employee IN (:users_managed)
            AND pi.ownInterviewValidated IS NOT NULL
            AND pi.interviewValidated IS NOT NULL
        ";

        $parameters = [
            'users_managed' => $usersManaged,
        ];

        if ($search != null) {
            $dql .= " AND (LOWER(u.lastName) like :search or LOWER(u.firstName) like :search) ";
            $parameters['search'] = '%' . strtolower(addslashes($search)) . '%';
        }
        $dql .= "ORDER BY pi.created_at DESC";

        $query = $this->_em->createQuery($dql);
        $query->setParameters($parameters);
        $query->setFirstResult($offset);
        $query->setMaxResults(PaginationEnum::DEFAULT_NUMBER_ELEMENT);

        return new Paginator($query);
    }
}
