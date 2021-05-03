<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KnowMakeRepository")
 * @ORM\Table(name="know_make_annual_interview")
 */
class KnowMakeAnnualInterview
{
    use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="wording_skill", type="text", nullable=true)
     */
    private $wordingSkill;

    /**
     * @ORM\Column(name="grade", type="string", length=255, nullable=true)
     */
    private $grade;

    /**
     * @ORM\Column(name="collab_comment", type="text", nullable=true)
     */
    private $collabComment;

    /**
     * @ORM\Column(name="manager_comment", type="text", nullable=true)
     */
    private $managerComment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnnualInterview", inversedBy="knowMakes")
     * @ORM\JoinColumn(name="annual_interview_id", nullable=false)
     */
    private $annualInterview;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWordingSkill(): ?string
    {
        return $this->wordingSkill;
    }

    public function setWordingSkill(?string $wordingSkill): self
    {
        $this->wordingSkill = $wordingSkill;

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

    public function getCollabComment(): ?string
    {
        return $this->collabComment;
    }

    public function setCollabComment(?string $collabComment): self
    {
        $this->collabComment = $collabComment;

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
