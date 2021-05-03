<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProInterviewRepository")
 * @ORM\Table(name="pro_interviews")
 */
class ProInterview
{
    use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="proInterviews")
     * @ORM\JoinColumn(name="employee_id", nullable=false)
     */
    private $employee;

    /**
     * @ORM\Column(name="job_title", type="string", length=255, nullable=true)
     */
    private $jobTitle;

    /**
     * @ORM\Column(name="current_function", type="string", length=255, nullable=true)
     */
    private $currentFunction;

    /**
     * @ORM\Column(name="function_seniority", type="string", length=255, nullable=true)
     */
    private $functionSeniority;

    /**
     * @ORM\Column(name="affectation", type="string", length=255, nullable=true)
     */
    private $affectation;

    /**
     * @ORM\Column(name="interview_date", type="date", nullable=true)
     */
    private $interviewDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="lead_by", nullable=true)
     */
    private $leadBy;

    /**
     * @ORM\Column(name="evolution_curent_position_desc", type="text", nullable=true)
     */
    private $evolutionCurentPositionDesc;

    /**
     * @ORM\Column(name="evolution_current_position_expected_time", type="text", nullable=true)
     */
    private $evolutionCurrentPositionExpectedTime;

    /**
     * @ORM\Column(name="change_position_desc", type="text", nullable=true)
     */
    private $changePositionDesc;

    /**
     * @ORM\Column(name="change_position_expected_time", type="text", nullable=true)
     */
    private $changePositionExpectedTime;

    /**
     * @ORM\Column(name="skills", type="text", nullable=true)
     */
    private $skills;

    /**
     * @ORM\Column(name="action_envisaged", type="text", nullable=true)
     */
    private $actionEnvisaged;

    /**
     * @ORM\Column(name="formation_wishes", type="boolean", nullable=true)
     */
    private $formationWishes;

    /**
     * @ORM\Column(name="formation_wishes_type", type="text", nullable=true)
     */
    private $formationWishesType;

    /**
     * @ORM\Column(name="formation_wishes_desc", type="text", nullable=true)
     */
    private $formationWishesDesc;

    /**
     * @ORM\Column(name="formation_wishes_expected_time", type="text", nullable=true)
     */
    private $formationWishesExpectedTime;

    /**
     * @ORM\Column(name="geographic_mobility", type="boolean", nullable=true)
     */
    private $geographicMobility;

    /**
     * @ORM\Column(name="geographic_mobility_location", type="text", nullable=true)
     */
    private $geographicMobilityLocation;

    /**
     * @ORM\Column(name="geographic_mobility_expected_time", type="text", nullable=true)
     */
    private $geographicMobilityExpectedTime;

    /**
     * @ORM\Column(name="group_mobility_location", type="text", nullable=true)
     */
    private $groupMobilityLocation;

    /**
     * @ORM\Column(name="group_mobility_expected_time", type="text", nullable=true)
     */
    private $groupMobilityExpectedTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="manager_id", nullable=true)
     */
    private $manager;

    /**
     * @ORM\Column(name="manager_opinion", type="string", length=255, nullable=true)
     */
    private $managerOpinion;

    /**
     * @ORM\Column(name="manager_date_signature", type="date", nullable=true)
     */
    private $managerDateSignature;

    /**
     * @ORM\Column(name="manager_signature", type="text", nullable=true)
     */
    private $managerSignature;

    /**
     * @ORM\Column(name="employee_opinion", type="string", length=255, nullable=true)
     */
    private $employeeOpinion;

    /**
     * @ORM\Column(name="employee_date_signature", type="date", nullable=true)
     */
    private $employeeDateSignature;

    /**
     * @ORM\Column(name="employee_signature", type="text", nullable=true)
     */
    private $employeeSignature;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="second_manager_id", nullable=true)
     */
    private $secondManage;

    /**
     * @ORM\Column(name="second_manager_date_signature", type="date", nullable=true)
     */
    private $secondManagerDateSignature;

    /**
     * @ORM\Column(name="second_manager_signature", type="text", nullable=true)
     */
    private $secondManagerSignature;

    /**
     * @ORM\Column(name="own_interview_validated", type="datetime", nullable=true)
     */
    private $ownInterviewValidated;

    /**
     * @ORM\Column(name="interview_validated", type="datetime", nullable=true)
     */
    private $interviewValidated;

    /**
     * @ORM\Column(name="accept_second_manager", type="boolean", options={"default":"1"})
     */
    private $acceptSecondManager = true;

    public static function getAffectionChoice()
    {
        return [
            'Direction' => 'Direction',
            'Fonction transverse' => 'Fonction transverse',
            'Projet' => 'Projet',
            'Infrastructure' => 'Infrastructure',
            'Architecture & Innovation' => 'Architecture & Innovation',
            'Control room' => 'Control room',
            'Production' => 'Production'
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getCurrentFunction(): ?string
    {
        return $this->currentFunction;
    }

    public function setCurrentFunction(?string $currentFunction): self
    {
        $this->currentFunction = $currentFunction;

        return $this;
    }

    public function getFunctionSeniority(): ?string
    {
        return $this->functionSeniority;
    }

    public function setFunctionSeniority(?string $functionSeniority): self
    {
        $this->functionSeniority = $functionSeniority;

        return $this;
    }

    public function getAffectation(): ?string
    {
        return $this->affectation;
    }

    public function setAffectation(?string $affectation): self
    {
        $this->affectation = $affectation;

        return $this;
    }

    public function getInterviewDate(): ?\DateTimeInterface
    {
        return $this->interviewDate;
    }

    public function setInterviewDate(?\DateTimeInterface $interviewDate): self
    {
        $this->interviewDate = $interviewDate;

        return $this;
    }

    public function getEvolutionCurentPositionDesc(): ?string
    {
        return $this->evolutionCurentPositionDesc;
    }

    public function setEvolutionCurentPositionDesc(?string $evolutionCurentPositionDesc): self
    {
        $this->evolutionCurentPositionDesc = $evolutionCurentPositionDesc;

        return $this;
    }

    public function getEvolutionCurrentPositionExpectedTime(): ?string
    {
        return $this->evolutionCurrentPositionExpectedTime;
    }

    public function setEvolutionCurrentPositionExpectedTime(?string $evolutionCurrentPositionExpectedTime): self
    {
        $this->evolutionCurrentPositionExpectedTime = $evolutionCurrentPositionExpectedTime;

        return $this;
    }

    public function getChangePositionDesc(): ?string
    {
        return $this->changePositionDesc;
    }

    public function setChangePositionDesc(?string $changePositionDesc): self
    {
        $this->changePositionDesc = $changePositionDesc;

        return $this;
    }

    public function getChangePositionExpectedTime(): ?string
    {
        return $this->changePositionExpectedTime;
    }

    public function setChangePositionExpectedTime(?string $changePositionExpectedTime): self
    {
        $this->changePositionExpectedTime = $changePositionExpectedTime;

        return $this;
    }

    public function getSkills(): ?string
    {
        return $this->skills;
    }

    public function setSkills(?string $skills): self
    {
        $this->skills = $skills;

        return $this;
    }

    public function getActionEnvisaged(): ?string
    {
        return $this->actionEnvisaged;
    }

    public function setActionEnvisaged(?string $actionEnvisaged): self
    {
        $this->actionEnvisaged = $actionEnvisaged;

        return $this;
    }

    public function getFormationWishes(): ?bool
    {
        return $this->formationWishes;
    }

    public function setFormationWishes(?bool $formationWishes): self
    {
        $this->formationWishes = $formationWishes;

        return $this;
    }

    public function getFormationWishesType(): ?string
    {
        return $this->formationWishesType;
    }

    public function setFormationWishesType(?string $formationWishesType): self
    {
        $this->formationWishesType = $formationWishesType;

        return $this;
    }

    public function getFormationWishesDesc(): ?string
    {
        return $this->formationWishesDesc;
    }

    public function setFormationWishesDesc(?string $formationWishesDesc): self
    {
        $this->formationWishesDesc = $formationWishesDesc;

        return $this;
    }

    public function getFormationWishesExpectedTime(): ?string
    {
        return $this->formationWishesExpectedTime;
    }

    public function setFormationWishesExpectedTime(?string $formationWishesExpectedTime): self
    {
        $this->formationWishesExpectedTime = $formationWishesExpectedTime;

        return $this;
    }

    public function getGeographicMobility(): ?bool
    {
        return $this->geographicMobility;
    }

    public function setGeographicMobility(?bool $geographicMobility): self
    {
        $this->geographicMobility = $geographicMobility;

        return $this;
    }

    public function getGeographicMobilityLocation(): ?string
    {
        return $this->geographicMobilityLocation;
    }

    public function setGeographicMobilityLocation(?string $geographicMobilityLocation): self
    {
        $this->geographicMobilityLocation = $geographicMobilityLocation;

        return $this;
    }

    public function getGeographicMobilityExpectedTime(): ?string
    {
        return $this->geographicMobilityExpectedTime;
    }

    public function setGeographicMobilityExpectedTime(?string $geographicMobilityExpectedTime): self
    {
        $this->geographicMobilityExpectedTime = $geographicMobilityExpectedTime;

        return $this;
    }

    public function getGroupMobilityLocation(): ?string
    {
        return $this->groupMobilityLocation;
    }

    public function setGroupMobilityLocation(?string $groupMobilityLocation): self
    {
        $this->groupMobilityLocation = $groupMobilityLocation;

        return $this;
    }

    public function getGroupMobilityExpectedTime(): ?string
    {
        return $this->groupMobilityExpectedTime;
    }

    public function setGroupMobilityExpectedTime(?string $groupMobilityExpectedTime): self
    {
        $this->groupMobilityExpectedTime = $groupMobilityExpectedTime;

        return $this;
    }

    public function getManagerOpinion(): ?string
    {
        return $this->managerOpinion;
    }

    public function setManagerOpinion(?string $managerOpinion): self
    {
        $this->managerOpinion = $managerOpinion;

        return $this;
    }

    public function getManagerDateSignature(): ?\DateTimeInterface
    {
        return $this->managerDateSignature;
    }

    public function setManagerDateSignature(?\DateTimeInterface $managerDateSignature): self
    {
        $this->managerDateSignature = $managerDateSignature;

        return $this;
    }

    public function getManagerSignature(): ?string
    {
        return $this->managerSignature;
    }

    public function setManagerSignature(?string $managerSignature): self
    {
        $this->managerSignature = $managerSignature;

        return $this;
    }

    public function getEmployeeOpinion(): ?string
    {
        return $this->employeeOpinion;
    }

    public function setEmployeeOpinion(?string $employeeOpinion): self
    {
        $this->employeeOpinion = $employeeOpinion;

        return $this;
    }

    public function getEmployeeDateSignature(): ?\DateTimeInterface
    {
        return $this->employeeDateSignature;
    }

    public function setEmployeeDateSignature(?\DateTimeInterface $employeeDateSignature): self
    {
        $this->employeeDateSignature = $employeeDateSignature;

        return $this;
    }

    public function getEmployeeSignature(): ?string
    {
        return $this->employeeSignature;
    }

    public function setEmployeeSignature(?string $employeeSignature): self
    {
        $this->employeeSignature = $employeeSignature;

        return $this;
    }

    public function getSecondManagerDateSignature(): ?\DateTimeInterface
    {
        return $this->secondManagerDateSignature;
    }

    public function setSecondManagerDateSignature(?\DateTimeInterface $secondManagerDateSignature): self
    {
        $this->secondManagerDateSignature = $secondManagerDateSignature;

        return $this;
    }

    public function getSecondManagerSignature(): ?string
    {
        return $this->secondManagerSignature;
    }

    public function setSecondManagerSignature(?string $secondManagerSignature): self
    {
        $this->secondManagerSignature = $secondManagerSignature;

        return $this;
    }

    public function getInterviewValidated(): ?\DateTimeInterface
    {
        return $this->interviewValidated;
    }

    public function setInterviewValidated(?\DateTimeInterface $interviewValidated): self
    {
        $this->interviewValidated = $interviewValidated;

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

    public function getLeadBy(): ?User
    {
        return $this->leadBy;
    }

    public function setLeadBy(?User $leadBy): self
    {
        $this->leadBy = $leadBy;

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

    public function getSecondManage(): ?User
    {
        return $this->secondManage;
    }

    public function setSecondManage(?User $secondManage): self
    {
        $this->secondManage = $secondManage;

        return $this;
    }

    public function getOwnInterviewValidated(): ?\DateTimeInterface
    {
        return $this->ownInterviewValidated;
    }

    public function setOwnInterviewValidated(?\DateTimeInterface $ownInterviewValidated): self
    {
        $this->ownInterviewValidated = $ownInterviewValidated;

        return $this;
    }

    public function getAcceptSecondManager(): ?bool
    {
        return $this->acceptSecondManager;
    }

    public function setAcceptSecondManager(bool $acceptSecondManager): self
    {
        $this->acceptSecondManager = $acceptSecondManager;

        return $this;
    }
}
