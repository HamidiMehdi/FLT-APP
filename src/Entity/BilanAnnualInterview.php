<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BilanRepository")
 * @ORM\Table(name="bilans_annual_interview")
 */
class BilanAnnualInterview
{
    use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="achievement", type="text", nullable=true)
     */
    private $achievement;

    /**
     * @ORM\Column(name="grade", type="text", nullable=true)
     */
    private $grade;

    /**
     * @ORM\Column(name="comment_collab", type="text", nullable=true)
     */
    private $commentCollab;

    /**
     * @ORM\Column(name="comment_manager", type="text", nullable=true)
     */
    private $commentManager;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnnualInterview", inversedBy="bilans")
     * @ORM\JoinColumn(name="annual_interview_id", nullable=false)
     */
    private $annualInterview;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAchievement(): ?string
    {
        return $this->achievement;
    }

    public function setAchievement(?string $achievement): self
    {
        $this->achievement = $achievement;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getCommentCollab(): ?string
    {
        return $this->commentCollab;
    }

    public function setCommentCollab(?string $commentCollab): self
    {
        $this->commentCollab = $commentCollab;

        return $this;
    }

    public function getCommentManager(): ?string
    {
        return $this->commentManager;
    }

    public function setCommentManager(?string $commentManager): self
    {
        $this->commentManager = $commentManager;

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
