<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntityTrait;
use App\Enum\WorkingEnum;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkingRepository")
 * @ORM\Table(name="workings")
 */
class Working
{
    use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="workings")
     * @ORM\JoinColumn(name="employee_id", nullable=false)
     */
    private $employee;

    /**
     * @ORM\Column(name="start_at", type="date")
     */
    private $startAt;

    /**
     * @ORM\Column(name="period_start_at", type="text")
     */
    private $periodStartAt;

    /**
     * @ORM\Column(name="end_at", type="date")
     */
    private $endAt;

    /**
     * @ORM\Column(name="period_end_at", type="text")
     */
    private $periodEndAt;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="decision", type="datetime", nullable=true)
     */
    private $decision;

    /**
     * @ORM\Column(name="is_accepted", type="boolean",  options={"default":"1"}))
     */
    private $isAccepted = true;

    /**
     * @ORM\Column(name="description_working_reject", type="text", nullable=true)
     */
    private $descriptionWorkingReject;

    /**
     * @ORM\Column(name="report_request", type="boolean", options={"default":"0"}))
     */
    private $reportRequest = false;

    /**
     * @ORM\Column(name="report", type="text", nullable=true)
     */
    private $report;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="decided_by", nullable=true)
     */
    private $decidedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getPeriodStartAt(): ?string
    {
        return $this->periodStartAt;
    }

    public function setPeriodStartAt(string $periodStartAt): self
    {
        $this->periodStartAt = $periodStartAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getPeriodEndAt(): ?string
    {
        return $this->periodEndAt;
    }

    public function setPeriodEndAt(string $periodEndAt): self
    {
        $this->periodEndAt = $periodEndAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDecision(): ?\DateTimeInterface
    {
        return $this->decision;
    }

    public function setDecision(?\DateTimeInterface $decision): self
    {
        $this->decision = $decision;

        return $this;
    }

    public function getIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(?bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    public function getDescriptionWorkingReject(): ?string
    {
        return $this->descriptionWorkingReject;
    }

    public function setDescriptionWorkingReject(?string $descriptionWorkingReject): self
    {
        $this->descriptionWorkingReject = $descriptionWorkingReject;

        return $this;
    }

    public function getReportRequest(): ?bool
    {
        return $this->reportRequest;
    }

    public function setReportRequest(bool $reportRequest): self
    {
        $this->reportRequest = $reportRequest;

        return $this;
    }

    public function getReport(): ?string
    {
        return $this->report;
    }

    public function setReport(?string $report): self
    {
        $this->report = $report;

        return $this;
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

    public function getDecidedBy(): ?User
    {
        return $this->decidedBy;
    }

    public function setDecidedBy(?User $decidedBy): self
    {
        $this->decidedBy = $decidedBy;

        return $this;
    }

    /**
     * @return float|int
     * @throws \Exception
     */
    public function getDaysInterval()
    {
        $start = $this->startAt;
        $end = $this->endAt;

        if ($start == $end) {
            return $this->periodStartAt === $this->periodEndAt ? 0.5 : 1;
        }

        $isWeekday = function (\DateTime $date) {
            return $date->format('N') < 6;
        };

        $days = $isWeekday($end) ? 1 : 0;
        while ($start->diff($end)->days > 0) {
            $days += $isWeekday($start) ? 1 : 0;
            $start = $start->add(new \DateInterval("P1D"));
        }

        // Si dernier jour est un jour de weekend, on comptabilise pas la période
        if (!$isWeekday($end) && $this->periodStartAt === WorkingEnum::MORNING) {
            return $days;
        } elseif (!$isWeekday($end) && $this->periodStartAt === WorkingEnum::AFTERNOON || $this->periodStartAt === $this->periodEndAt) {
            return $days - 0.5;
        } elseif ($this->periodStartAt === WorkingEnum::AFTERNOON) {
            return $days - 1;
        }

        return $days;
    }
}
