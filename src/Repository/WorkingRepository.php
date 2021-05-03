<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Working;
use App\Enum\PaginationEnum;
use App\Enum\WorkingEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DoctrineExtensions\Query\Mysql\Date;

/**
 * @method Working|null find($id, $lockMode = null, $lockVersion = null)
 * @method Working|null findOneBy(array $criteria, array $orderBy = null)
 * @method Working[]    findAll()
 * @method Working[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Working::class);
    }

    /**
     * Returns all work requests by a user
     * @param User $user
     * @param $dateFilter
     * @param $status
     * @param $offset
     * @return Paginator
     */
    public function getWorkingByUser(User $user, $dateFilter, $status, $offset)
    {
        $dql = "
            SELECT w
            FROM App\Entity\Working w
            WHERE w.employee = :employee
        ";

        $parameters = ['employee' => $user->getId()];

        if ($dateFilter != null) {
            $dql .= " AND :date_filter BETWEEN w.startAt AND w.endAt ";
            $dateFilter = \DateTime::createFromFormat('d/m/Y', addslashes($dateFilter));
            $parameters['date_filter'] = $dateFilter->format('Y-m-d');
        }

        if ($status === WorkingEnum::PENDING_STATUS) {
            $dql .= " AND w.decidedBy IS NULL AND w.decision IS NULL ";
        } elseif ($status === WorkingEnum::REFUSED_STATUS) {
            $dql .= " AND w.decidedBy IS NOT NULL AND w.decision IS NOT NULL AND w.isAccepted = FALSE ";
        } elseif ($status === WorkingEnum::ACCEPTED_STATUS) {
            $dql .= " AND w.decidedBy IS NOT NULL AND w.decision IS NOT NULL AND w.isAccepted = TRUE ";
        } else {
            $dql .= " AND w.decidedBy IS NOT NULL AND w.decision IS NOT NULL AND w.isAccepted = TRUE AND w.reportRequest = TRUE AND w.report IS NULL OR w.report = '' ";
        }

        $dql .= "ORDER BY w.created_at DESC";

        $query = $this->_em->createQuery($dql);
        $query->setParameters($parameters);
        $query->setFirstResult($offset);
        $query->setMaxResults(PaginationEnum::DEFAULT_NUMBER_ELEMENT);

        return new Paginator($query);
    }

    /**
     * Returns all work requests by a manager
     * @param User $user
     * @param $dateFilter
     * @param $status
     * @param $search
     * @param $offset
     * @return Paginator
     */
    public function getWorkingByManager(User $user, $dateFilter, $status, $search, $offset)
    {
        $usersManaged = $this->_em->getRepository(User::class)->getEmployeeIdManagedByUser($user);

        $dql = "
            SELECT w
            FROM App\Entity\Working w
            JOIN w.employee u
            WHERE w.employee IN (:userManaged)
        ";

        $parameters = ['userManaged' => $usersManaged];

        if ($dateFilter != null) {
            $dql .= " AND :date_filter BETWEEN w.startAt AND w.endAt ";
            $dateFilter = \DateTime::createFromFormat('d/m/Y', addslashes($dateFilter));
            $parameters['date_filter'] = $dateFilter->format('Y-m-d');
        }

        if ($status === WorkingEnum::PENDING_STATUS) {
            $dql .= " AND w.decidedBy IS NULL AND w.decision IS NULL ";
        } elseif ($status === WorkingEnum::REFUSED_STATUS) {
            $dql .= " AND w.decidedBy IS NOT NULL AND w.decision IS NOT NULL AND w.isAccepted = FALSE ";
        } elseif ($status === WorkingEnum::ACCEPTED_STATUS) {
            $dql .= " AND w.decidedBy IS NOT NULL AND w.decision IS NOT NULL AND w.isAccepted = TRUE ";
        } else {
            $dql .= " AND w.decidedBy IS NOT NULL AND w.decision IS NOT NULL AND w.isAccepted = TRUE AND w.reportRequest = TRUE AND w.report IS NULL OR w.report = '' ";
        }

        if ($search != null) {
            $dql .= " AND (LOWER(u.lastName) like :search or LOWER(u.firstName) like :search) ";
            $parameters['search'] = '%' . strtolower(addslashes($search)) . '%';
        }

        $dql .= "ORDER BY w.created_at DESC";

        $query = $this->_em->createQuery($dql);
        $query->setParameters($parameters);
        $query->setFirstResult($offset);
        $query->setMaxResults(PaginationEnum::DEFAULT_NUMBER_ELEMENT);

        return new Paginator($query);
    }

    /**
     * Retourne le nombre de demandes de télétravail acceptées par location pour une date
     * @param \DateTime $date
     * @return mixed
     */
    public function getWorkingsCounterPerLocationByDay(\DateTime $date)
    {
        $dql = "
            SELECT DISTINCT u.location as location, count(w) as counter
            FROM App\Entity\Working w
            JOIN w.employee u
            WHERE w.isAccepted = TRUE
            AND w.decidedBy IS NOT NULL
            AND w.decision IS NOT NULL
            AND :date BETWEEN w.startAt AND w.endAt
            GROUP BY u.location
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('date', $date->format('Y-m-d'));

        return $query->getResult();
    }

    /**
     * Retourne toutes les demandes de télétravail pour une date
     * @param \DateTime $date
     * @return mixed
     */
    public function getWorkingsByDay(\DateTime $date)
    {
        $dql = "
            SELECT w
            FROM App\Entity\Working w
            WHERE w.isAccepted = TRUE
            AND w.decidedBy IS NOT NULL
            AND w.decision IS NOT NULL
            AND :date BETWEEN w.startAt AND w.endAt
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('date', $date->format('Y-m-d'));

        return $query->getResult();
    }

    /**
     * Retourne toutes les demandes de télétravail pour un mois
     * @param \DateTime $startAt
     * @param \DateTime $endAt
     * @return mixed
     */
    public function getWorkingsByMonth(\DateTime $startAt, \DateTime $endAt)
    {
        $dql = "
            SELECT w
            FROM App\Entity\Working w
            WHERE w.isAccepted = TRUE
            AND w.decidedBy IS NOT NULL
            AND w.decision IS NOT NULL
            AND w.startAt <= :endAt
            AND w.endAt >= :startAt
        ";

        $parameters = [
            'startAt' => $startAt->format('Y-m-d'),
            'endAt' => $endAt->format('Y-m-d')
        ];

        $query = $this->_em->createQuery($dql);
        $query->setParameters($parameters);

        return $query->getResult();
    }

    /**
     * @param User $user
     * @param \DateTime $startAt
     * @param \DateTime $endAt
     * @return mixed
     */
    public function checkWorkingInterval(User $user, \DateTime $startAt, \DateTime $endAt)
    {
        $dql = "
            SELECT w
            FROM App\Entity\Working w
            WHERE (w.isAccepted = TRUE AND w.decidedBy IS NOT NULL AND w.decision IS NOT NULL
            OR w.decidedBy IS NULL AND w.decision IS NULL)
            AND w.employee = :user
            AND w.startAt <= :endAt
            AND w.endAt >= :startAt
        ";

        $parameters = [
            'user' => $user,
            'startAt' => $startAt->format('Y-m-d'),
            'endAt' => $endAt->format('Y-m-d')
        ];

        $query = $this->_em->createQuery($dql);
        $query->setParameters($parameters);

        return $query->getResult();
    }

    /**
     * @param $user
     * @param \DateTime $startAt
     * @param \DateTime $endAt
     * @param $location
     * @return mixed
     */
    public function getWorkingDataGraph($user, \DateTime $startAt, \DateTime $endAt, $location)
    {
        $dql = "
            SELECT w
            FROM App\Entity\Working w
            JOIN w.employee u
            WHERE w.isAccepted = TRUE AND w.decidedBy IS NOT NULL AND w.decision IS NOT NULL
            AND w.startAt <= :endAt
            AND w.endAt >= :startAt
        ";

        $parameters = [
            'startAt' => $startAt->format('Y-m-d'),
            'endAt' => $endAt->format('Y-m-d')
        ];

        if ($user) {
            $dql .= " AND w.employee = :user ";
            $parameters['user'] = $user;
        }

        if ($location) {
            $dql .= " AND u.location = :location";
            $parameters['location'] = $location;
        }

        $query = $this->_em->createQuery($dql);
        $query->setParameters($parameters);

        return $query->getResult();
    }
}
