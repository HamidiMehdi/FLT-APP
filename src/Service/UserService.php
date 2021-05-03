<?php

namespace App\Service;

use App\Entity\Manager;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /**  @var EntityManagerInterface */
    private $em;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Retourne le n+1 et n+2
     * @param User $employee
     * @return array
     */
    public function getAllManagersByEmployee(User $employee) :array
    {
        $firstManager = $this->em->getRepository(Manager::class)->findOneBy([
            'employee' => $employee
        ]);

        $secondManager = $this->em->getRepository(Manager::class)->findOneBy([
            'employee' => $firstManager ? $firstManager->getManager() : null
        ]);

        return [
            $firstManager ? $firstManager->getManager() : null,
            $secondManager ? $secondManager->getManager() : null,
            null
        ];
    }

    /**
     * @param $year
     * @return array
     */
    public function getDataGraphProByYear($year) {
        $notCreatedCounter = $this->em->getRepository(User::class)->getProInterviewNotCreatedByYear($year);
        $notValidatedCounter = $this->em->getRepository(User::class)->getProInterviewNotValidatedByYear($year);
        $validatedCounter = $this->em->getRepository(User::class)->getProInterviewValidatedByYear($year);

        return [
            count($notCreatedCounter),
            count($notValidatedCounter),
            count($validatedCounter)
        ];
    }

    /**
     * @param $year
     * @return array
     */
    public function getDataGraphAnnualByYear($year)
    {
        $notCreatedCounter = $this->em->getRepository(User::class)->getAnnualInterviewNotCreatedByYear($year);
        $notValidatedCounter = $this->em->getRepository(User::class)->getAnnualInterviewNotValidatedByYear($year);
        $validatedCounter = $this->em->getRepository(User::class)->getAnnualInterviewValidatedByYear($year);

        return [
            count($notCreatedCounter),
            count($notValidatedCounter),
            count($validatedCounter)
        ];
    }
}