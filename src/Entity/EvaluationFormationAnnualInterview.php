<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvaluationFormationAnnualInterviewRepository")
 * @ORM\Table(name="evaluation_formation_annual_interview")
 */
class EvaluationFormationAnnualInterview
{
    use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="formation", type="text", nullable=true)
     */
    private $formation;

    /**
     * @ORM\Column(name="employee_appreciation", type="text", nullable=true)
     */
    private $employeeAppreciation;

    /**
     * @ORM\Column(name="employee_comment", type="text", nullable=true)
     */
    private $employeeComment;

    /**
     * @ORM\Column(name="manager_appreciation", type="text", nullable=true)
     */
    private $managerAppreciation;

    /**
     * @ORM\Column(name="manager_comment", type="text", nullable=true)
     */
    private $managerComment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnnualInterview", inversedBy="evaluationFormationAnnualInterviews")
     * @ORM\JoinColumn(name="annual_interview_id", nullable=false)
     */
    private $annualInterview;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormation(): ?string
    {
        return $this->formation;
    }

    public function setFormation(?string $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getEmployeeAppreciation(): ?string
    {
        return $this->employeeAppreciation;
    }

    public function setEmployeeAppreciation(?string $employeeAppreciation): self
    {
        $this->employeeAppreciation = $employeeAppreciation;

        return $this;
    }

    public function getEmployeeComment(): ?string
    {
        return $this->employeeComment;
    }

    public function setEmployeeComment(?string $employeeComment): self
    {
        $this->employeeComment = $employeeComment;

        return $this;
    }

    public function getManagerAppreciation(): ?string
    {
        return $this->managerAppreciation;
    }

    public function setManagerAppreciation(?string $managerAppreciation): self
    {
        $this->managerAppreciation = $managerAppreciation;

        return $this;
    }

    public function getManagerComment(): ?string
    {
        return $this->managerComment;
    }

    public function setManagerComment(?string $managerComment): self
    {
        $this->managerComment = $managerComment;

        return $this;
    }

    public function getAnnualInterview(): ?AnnualInterview
    {
        return $this->annualInterview;
    }

    public function setAnnualInterview(?AnnualInterview $annualInterview): self
    {
        $this->annualInterview = $annualInterview;

        return $this;
    }
}
