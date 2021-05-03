<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormationAnnualInterviewRepository")
 * @ORM\Table(name="formation_annual_interview")
 */
class FormationAnnualInterview
{
    use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="year", type="text", nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(name="duratrion", type="text", nullable=true)
     */
    private $formation;

    /**
     * @ORM\Column(name="start", type="text", nullable=true)
     */
    private $start;

    /**
     * @ORM\Column(name="end", type="text", nullable=true)
     */
    private $end;

    /**
     * @ORM\Column(name="duration", type="text", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(name="organisme", type="text", nullable=true)
     */
    private $organisme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnnualInterview", inversedBy="formationAnnualInterview")
     * @ORM\JoinColumn(name="annual_interview_id", nullable=false)
     */
    private $annualInterview;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

        return $this;
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

    public function getStart(): ?string
    {
        return $this->start;
    }

    public function setStart(?string $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?string
    {
        return $this->end;
    }

    public function setEnd(?string $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getOrganisme(): ?string
    {
        return $this->organisme;
    }

    public function setOrganisme(?string $organisme): self
    {
        $this->organisme = $organisme;

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
