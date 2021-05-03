<?php

namespace App\Repository;

use App\Entity\User;
use App\Enum\PaginationEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Retourne un tableau d'id des collaborateurs de $user (n-1 , n-2, ...)
     * @param User $user
     * @return array
     */
    public function getEmployeeIdManagedByUser(User $user)
    {
        if ($user->isAdmin()) {
            return $this->getAllUsersId($user);
        }

        $manager = [$user->getId()];
        $history = [];

        $finish = false;
        while (!$finish) {

            $dql = "
                SELECT IDENTITY(m.employee) as id
                FROM App\Entity\Manager m
                WHERE m.manager = :managers
            ";

            $query = $this->_em->createQuery($dql);
            $query->setParameter('managers', $manager);
            $result = $query->getResult();

            $manager = [];
            $counter = 0;
            foreach ($result as $employee) {
                if ($employee['id'] == $user->getId() || in_array($employee['id'], $history)) {
                    continue;
                }
                $history[] = $employee['id'];
                $manager[] = $employee['id'];
                $counter++;
            }

            if ($counter === 0) {
                $finish = true;
            }
        }

        return $history;
    }

    /**
     * Retourne un tableau d'id des managers de $user (n+1, n+2, ...)
     * Return user id | array
     * @param User $user
     * @return array
     */
    public function getManagersIdByUser(User $user)
    {
        $employees = [$user->getId()];
        $history = [];

        $finish = false;
        while (!$finish) {

            $dql = "
                SELECT IDENTITY(m.manager) as id
                FROM App\Entity\Manager m
                WHERE m.employee = :employees
            ";

            $query = $this->_em->createQuery($dql);
            $query->setParameter('employees', $employees);
            $result = $query->getResult();

            $employees = [];
            $counter = 0;
            foreach ($result as $manager) {
                if ($manager['id'] == $user->getId() || in_array($manager['id'], $history)) {
                    continue;
                }
                $history[] = $manager['id'];
                $employees[] = $manager['id'];
                $counter++;
            }

            if ($counter === 0) {
                $finish = true;
            }
        }

        return $history;
    }

    /**
     * Return user object | array
     * @param User $user
     * @return mixed
     */
    public function getManagersEntityByUser(User $user)
    {
        $managersId = $this->getManagersIdByUser($user);

        $dql = "
            SELECT u
            FROM App\Entity\User u
            WHERE u.id IN (:managers)
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('managers', $managersId);

        return $query->getResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function getAllUsersId(User $user)
    {
        $dql = "
            SELECT u.id as id
            FROM App\Entity\User u
            WHERE u.id != :user
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('user', $user->getId());

        $result = [];
        foreach ($query->getArrayResult() as $userResult) {
            $result[] = $userResult['id'];
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getUsersCounter()
    {
        $dql = "
            SELECT COUNT(u) as counter
            FROM App\Entity\User u
        ";

        $query = $this->_em->createQuery($dql);
        $result = $query->getResult();

        return $result[0]['counter'];
    }

    /**
     * @return mixed
     */
    public function getAdminsCounter()
    {
        $dql = "
            SELECT COUNT(u) as counter
            FROM App\Entity\User u
            WHERE u.roles like :role
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('role', '%ROLE_ADMIN%');
        $result = $query->getResult();

        return $result[0]['counter'];
    }

    /**
     * @param $year
     * @return mixed
     */
    public function getProInterviewNotCreatedByYear($year)
    {
        $dql = "
            SELECT u.id
            FROM App\Entity\User u
            WHERE u.id NOT IN (
                SELECT IDENTITY(pi.employee)
                FROM App\Entity\ProInterview pi
                WHERE YEAR(pi.created_at) = :year
                AND pi.ownInterviewValidated IS NOT NULL
            )
            AND u.deleted_at IS NULL
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('year', $year);

        return array_map(function ($value) {
            return $value['id'];
        }, $query->getResult());
    }

    /**
     * @param $year
     * @return mixed
     */
    public function getProInterviewNotValidatedByYear($year)
    {
        $dql = "
            SELECT IDENTITY(pi.employee) as id
            FROM App\Entity\ProInterview pi
            WHERE YEAR(pi.created_at) = :year
            AND pi.ownInterviewValidated IS NOT NULL
            AND pi.interviewValidated IS NULL
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('year', $year);

        return array_map(function ($value) {
            return $value['id'];
        }, $query->getResult());
    }

    /**
     * @param $year
     * @return mixed
     */
    public function getProInterviewValidatedByYear($year)
    {
        $dql = "
            SELECT IDENTITY(pi.employee) as id
            FROM App\Entity\ProInterview pi
            WHERE YEAR(pi.created_at) = :year
            AND pi.ownInterviewValidated IS NOT NULL
            AND pi.interviewValidated IS NOT NULL
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('year', $year);

        return array_map(function ($value) {
            return $value['id'];
        }, $query->getResult());
    }

    /**
     * @param $year
     * @return mixed
     */
    public function getAnnualInterviewNotCreatedByYear($year)
    {
        $dql = "
            SELECT u.id
            FROM App\Entity\User u
            WHERE u.id NOT IN (
                SELECT IDENTITY(ai.employee)
                FROM App\Entity\AnnualInterview ai
                WHERE YEAR(ai.created_at) = :year
                AND ai.ownInterviewValidated IS NOT NULL
            )
            AND u.deleted_at IS NULL
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('year', $year);

        return array_map(function ($value) {
            return $value['id'];
        }, $query->getResult());
    }

    /**
     * @param $year
     * @return mixed
     */
    public function getAnnualInterviewNotValidatedByYear($year)
    {
        $dql = "
            SELECT IDENTITY(ai.employee) as id
            FROM App\Entity\AnnualInterview ai
            WHERE YEAR(ai.created_at) = :year
            AND ai.ownInterviewValidated IS NOT NULL
            AND ai.interviewValidated IS NULL
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('year', $year);

        return array_map(function ($value) {
            return $value['id'];
        }, $query->getResult());
    }

    /**
     * @param $year
     * @return mixed
     */
    public function getAnnualInterviewValidatedByYear($year)
    {
        $dql = "
            SELECT IDENTITY(ai.employee) as id
            FROM App\Entity\AnnualInterview ai
            WHERE YEAR(ai.created_at) = :year
            AND ai.ownInterviewValidated IS NOT NULL
            AND ai.interviewValidated IS NOT NULL
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('year', $year);

        return array_map(function ($value) {
            return $value['id'];
        }, $query->getResult());
    }

    /**
     * @param $offset
     * @param $search
     * @return Paginator
     */
    public function getUsersDashboard($offset, $search)
    {
        $dql = "
            SELECT u
            FROM App\Entity\User u
            WHERE u.deleted_at IS NULL
        ";

        $parameters = [];
        if ($search != null) {
            $dql .= "
                AND (LOWER(u.lastName) like :search or LOWER(u.firstName) like :search) 
            ";
            $parameters['search'] = '%' . strtolower(addslashes($search)) . '%';
        }
        $dql .= "ORDER BY u.lastConnection asc";

        $query = $this->_em->createQuery($dql);
        $query->setParameters($parameters);
        $query->setFirstResult($offset);
        $query->setMaxResults(PaginationEnum::DEFAULT_NUMBER_ELEMENT);

        return new Paginator($query);
    }

    /**
     * @return mixed
     */
    public function getUserCounterPerLocation()
    {
        $dql = "
            SELECT DISTINCT u.location as location, count(u) as counter
            FROM App\Entity\User u
            WHERE u.deleted_at IS NULL
            GROUP BY u.location
        ";

        $result = [];
        $query = $this->_em->createQuery($dql);
        $resultQuery = $query->getResult();

        foreach ($resultQuery as $location) {
            if (!key_exists($location['location'], $result)) {
                $result[$location['location']] = $location['counter'];
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAllLocations()
    {
        $dql = "
            SELECT DISTINCT u.location as location
            FROM App\Entity\User u
            WHERE u.deleted_at IS NULL
        ";


        $query = $this->_em->createQuery($dql);
        $resultQuery = $query->getResult();

        $result = [];
        foreach ($resultQuery as $location) {
            $result[ucwords(strtolower($location['location']))] = $location['location'];
        }

        return $result;
    }
}