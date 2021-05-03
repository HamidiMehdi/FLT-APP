<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ManagerRepository")
 * @ORM\Table(name="managers")
 */
class Manager
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    private $employee;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     */
    private $manager;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }
}
