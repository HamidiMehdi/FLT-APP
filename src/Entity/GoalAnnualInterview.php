<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GoalAnnualInterviewRepository")
 * @ORM\Table(name="goal_annual_interview")
 */
class GoalAnnualInterview
{
    use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="wording_goal", type="text", nullable=true)
     */
    private $wordingGoal;

    /**
     * @ORM\Column(name="goal_achieve", type="text", nullable=true)
     */
    private $goalAchieve;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnnualInterview", inversedBy="goalAnnualInterviews")
     * @ORM\JoinColumn(name="annual_interview_id", nullable=false)
     */
    private $annualInterview;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWordingGoal(): ?string
    {
        return $this->wordingGoal;
    }

    public function setWordingGoal(?string $wordingGoal): self
    {
        $this->wordingGoal = $wordingGoal;

        return $this;
    }

    public function getGoalAchieve(): ?string
    {
        return $this->goalAchieve;
    }

    public function setGoalAchieve(?string $goalAchieve): self
    {
        $this->goalAchieve = $goalAchieve;

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
