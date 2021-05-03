<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnualInterviewRepository")
 * @ORM\Table(name="annual_interviews")
 */
class AnnualInterview
{
    use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="annualInterviews")
     * @ORM\JoinColumn(name="employee_id", nullable=false)
     */
    private $employee;

    /**
     * @ORM\Column(name="job_title", type="text", nullable=true)
     */
    private $jobTitle;

    /**
     * @ORM\Column(name="current_function", type="text", nullable=true)
     */
    private $currentFunction;

    /**
     * @ORM\Column(name="function_seniority", type="text", nullable=true)
     */
    private $functionSeniority;

    /**
     * @ORM\Column(name="affectation", type="string", length=255, nullable=true)
     */
    private $affectation;

    /**
     * @ORM\Column(name="date_last_annual_interview", type="date", nullable=true)
     */
    private $dateLastAnnualInterview;

    /**
     * @ORM\Column(name="last_annual_interview_lead_by", type="string", length=255, nullable=true)
     */
    private $lastAnnualInterviewLeadBy;

    /**
     * @ORM\Column(name="previous_evaluation", type="text", nullable=true)
     */
    private $previousEvaluation;

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
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\BilanAnnualInterview",
     *     mappedBy="annualInterview",
     *     cascade={"persist", "remove"},
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true
     * )
     */
    private $bilans;

    /**
     * @ORM\Column(name="bilan_avg", type="integer", nullable=true)
     */
    private $bilanAvg;

    /**
     * @ORM\Column(name="comment_collab_working_env", type="text", nullable=true)
     */
    private $commentCollabWorkingEnv;

    /**
     * @ORM\Column(name="comment_manager_working_env", type="string", length=255, nullable=true)
     */
    private $commentManagerWorkingEnv;

    /**
     * @ORM\Column(name="comment_manager_strong_points", type="text", nullable=true)
     */
    private $commentManagerStrongPoints;

    /**
     * @ORM\Column(name="comment_collab_improvement", type="text", nullable=true)
     */
    private $commentCollabImprovement;

    /**
     * @ORM\Column(name="comment_manager_improvement", type="text", nullable=true)
     */
    private $commentManagerImprovement;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\KnowHowAnnualInterview",
     *     mappedBy="annualInterview",
     *     cascade={"persist", "remove"},
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true
     * )
     */
    private $knowHows;

    /**
     * @ORM\Column(name="know_how_avg", type="integer", nullable=true)
     */
    private $knowHowAvg;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\KnowMakeAnnualInterview",
     *     mappedBy="annualInterview",
     *     cascade={"persist", "remove"},
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true
     * )
     */
    private $knowMakes;

    /**
     * @ORM\Column(name="know_make_avg", type="integer", nullable=true)
     */
    private $knowMakeAvg;

    /**
     * @ORM\Column(name="know_make_comment", type="text", nullable=true)
     */
    private $knowMakeComment;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\FormationAnnualInterview",
     *     mappedBy="annualInterview",
     *     cascade={"persist", "remove"},
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true
     * )
     */
    private $formationAnnualInterview;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\EvaluationFormationAnnualInterview",
     *     mappedBy="annualInterview",
     *     cascade={"persist", "remove"},
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true
     * )
     */
    private $evaluationFormationAnnualInterviews;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\FormationDesiredAnnualInterview",
     *     mappedBy="annualInterview",
     *     cascade={"persist", "remove"},
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true
     * )
     */
    private $formationDesiredAnnualInterviews;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\GoalAnnualInterview",
     *     mappedBy="annualInterview",
     *     cascade={"persist", "remove"},
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true
     * )
     */
    private $goalAnnualInterviews;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="manager_id", nullable=true)
     */
    private $manager;

    /**
     * @ORM\Column(name="manager_opigion", type="text", nullable=true)
     */
    private $managerOpigion;

    /**
     * @ORM\Column(name="manager_date_signature", type="datetime", nullable=true)
     */
    private $managerDateSignature;

    /**
     * @ORM\Column(name="manager_signature", type="text", nullable=true)
     */
    private $managerSignature;

    /**
     * @ORM\Column(name="is_validated", type="text", nullable=true)
     */
    private $employeeOpinion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $employeeDateSignature;

    /**
     * @ORM\Column(name="employee_signature", type="text", nullable=true)
     */
    private $employeeSignature;

    /**
     * @ORM\Column(name="refuse_signature", type="boolean", nullable=true)
     */
    private $refuseSignature;

    /**
     * @ORM\Column(name="own_interview_validated", type="datetime", nullable=true)
     */
    private $ownInterviewValidated;

    /**
     * @ORM\Column(name="interview_validated", type="datetime", nullable=true)
     */
    private $interviewValidated;

    public function __construct()
    {
        $this->bilans = new ArrayCollection();
        $this->knowHows = new ArrayCollection();
        $this->knowMakes = new ArrayCollection();
        $this->evaluationFormationAnnualInterviews = new ArrayCollection();
        $this->formationDesiredAnnualInterviews = new ArrayCollection();
        $this->formationAnnualInterview = new ArrayCollection();
        $this->goalAnnualInterviews = new ArrayCollection();
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

    public function setFunctionSeniority(string $functionSeniority): self
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

    public function getDateLastAnnualInterview(): ?\DateTimeInterface
    {
        return $this->dateLastAnnualInterview;
    }

    public function setDateLastAnnualInterview(?\DateTimeInterface $dateLastAnnualInterview): self
    {
        $this->dateLastAnnualInterview = $dateLastAnnualInterview;

        return $this;
    }

    public function getLastAnnualInterviewLeadBy(): ?string
    {
        return $this->lastAnnualInterviewLeadBy;
    }

    public function setLastAnnualInterviewLeadBy(?string $lastAnnualInterviewLeadBy): self
    {
        $this->lastAnnualInterviewLeadBy = $lastAnnualInterviewLeadBy;

        return $this;
    }

    public function getPreviousEvaluation(): ?string
    {
        return $this->previousEvaluation;
    }

    public function setPreviousEvaluation(?string $previousEvaluation): self
    {
        $this->previousEvaluation = $previousEvaluation;

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

    public function getBilanAvg(): ?string
    {
        return $this->bilanAvg;
    }

    public function setBilanAvg(?string $bilanAvg): self
    {
        $this->bilanAvg = $bilanAvg;

        return $this;
    }

    public function getCommentCollabWorkingEnv(): ?string
    {
        return $this->commentCollabWorkingEnv;
    }

    public function setCommentCollabWorkingEnv(?string $commentCollabWorkingEnv): self
    {
        $this->commentCollabWorkingEnv = $commentCollabWorkingEnv;

        return $this;
    }

    public function getCommentManagerWorkingEnv(): ?string
    {
        return $this->commentManagerWorkingEnv;
    }

    public function setCommentManagerWorkingEnv(?string $commentManagerWorkingEnv): self
    {
        $this->commentManagerWorkingEnv = $commentManagerWorkingEnv;

        return $this;
    }

    public function getCommentManagerStrongPoints(): ?string
    {
        return $this->commentManagerStrongPoints;
    }

    public function setCommentManagerStrongPoints(?string $commentManagerStrongPoints): self
    {
        $this->commentManagerStrongPoints = $commentManagerStrongPoints;

        return $this;
    }

    public function getCommentCollabImprovement(): ?string
    {
        return $this->commentCollabImprovement;
    }

    public function setCommentCollabImprovement(?string $commentCollabImprovement): self
    {
        $this->commentCollabImprovement = $commentCollabImprovement;

        return $this;
    }

    public function getCommentManagerImprovement(): ?string
    {
        return $this->commentManagerImprovement;
    }

    public function setCommentManagerImprovement(?string $commentManagerImprovement): self
    {
        $this->commentManagerImprovement = $commentManagerImprovement;

        return $this;
    }

    public function getKnowHowAvg(): ?string
    {
        return $this->knowHowAvg;
    }

    public function setKnowHowAvg(?string $knowHowAvg): self
    {
        $this->knowHowAvg = $knowHowAvg;

        return $this;
    }

    public function getKnowMakeAvg(): ?string
    {
        return $this->knowMakeAvg;
    }

    public function setKnowMakeAvg(?string $knowMakeAvg): self
    {
        $this->knowMakeAvg = $knowMakeAvg;

        return $this;
    }

    public function getKnowMakeComment(): ?string
    {
        return $this->knowMakeComment;
    }

    public function setKnowMakeComment(?string $knowMakeComment): self
    {
        $this->knowMakeComment = $knowMakeComment;

        return $this;
    }

    public function getManagerOpigion(): ?string
    {
        return $this->managerOpigion;
    }

    public function setManagerOpigion(?string $managerOpigion): self
    {
        $this->managerOpigion = $managerOpigion;

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

    public function getRefuseSignature(): ?bool
    {
        return $this->refuseSignature;
    }

    public function setRefuseSignature(?bool $refuseSignature): self
    {
        $this->refuseSignature = $refuseSignature;

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

    /**
     * @return Collection|BilanAnnualInterview[]
     */
    public function getBilans(): Collection
    {
        return $this->bilans;
    }

    public function addBilan(BilanAnnualInterview $bilan): self
    {
        if (!$this->bilans->contains($bilan)) {
            $this->bilans[] = $bilan;
            $bilan->setAnnualInterview($this);
        }

        return $this;
    }

    public function removeBilan(BilanAnnualInterview $bilan): self
    {
        if ($this->bilans->contains($bilan)) {
            $this->bilans->removeElement($bilan);
            // set the owning side to null (unless already changed)
            if ($bilan->getAnnualInterview() === $this) {
                $bilan->setAnnualInterview(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|KnowHowAnnualInterview[]
     */
    public function getKnowHows(): Collection
    {
        return $this->knowHows;
    }

    public function addKnowHow(KnowHowAnnualInterview $knowHow): self
    {
        if (!$this->knowHows->contains($knowHow)) {
            $this->knowHows[] = $knowHow;
            $knowHow->setAnnualInterview($this);
        }

        return $this;
    }

    public function removeKnowHow(KnowHowAnnualInterview $knowHow): self
    {
        if ($this->knowHows->contains($knowHow)) {
            $this->knowHows->removeElement($knowHow);
            // set the owning side to null (unless already changed)
            if ($knowHow->getAnnualInterview() === $this) {
                $knowHow->setAnnualInterview(null);
            }
        }

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

    /**
     * @return Collection|KnowMakeAnnualInterview[]
     */
    public function getKnowMakes(): Collection
    {
        return $this->knowMakes;
    }

    public function addKnowMake(KnowMakeAnnualInterview $knowMake): self
    {
        if (!$this->knowMakes->contains($knowMake)) {
            $this->knowMakes[] = $knowMake;
            $knowMake->setAnnualInterview($this);
        }

        return $this;
    }

    public function removeKnowMake(KnowMakeAnnualInterview $knowMake): self
    {
        if ($this->knowMakes->contains($knowMake)) {
            $this->knowMakes->removeElement($knowMake);
            // set the owning side to null (unless already changed)
            if ($knowMake->getAnnualInterview() === $this) {
                $knowMake->setAnnualInterview(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EvaluationFormationAnnualInterview[]
     */
    public function getEvaluationFormationAnnualInterviews(): Collection
    {
        return $this->evaluationFormationAnnualInterviews;
    }

    public function addEvaluationFormationAnnualInterview(EvaluationFormationAnnualInterview $evaluationFormationAnnualInterview): self
    {
        if (!$this->evaluationFormationAnnualInterviews->contains($evaluationFormationAnnualInterview)) {
            $this->evaluationFormationAnnualInterviews[] = $evaluationFormationAnnualInterview;
            $evaluationFormationAnnualInterview->setAnnualInterview($this);
        }

        return $this;
    }

    public function removeEvaluationFormationAnnualInterview(EvaluationFormationAnnualInterview $evaluationFormationAnnualInterview): self
    {
        if ($this->evaluationFormationAnnualInterviews->contains($evaluationFormationAnnualInterview)) {
            $this->evaluationFormationAnnualInterviews->removeElement($evaluationFormationAnnualInterview);
            // set the owning side to null (unless already changed)
            if ($evaluationFormationAnnualInterview->getAnnualInterview() === $this) {
                $evaluationFormationAnnualInterview->setAnnualInterview(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FormationDesiredAnnualInterview[]
     */
    public function getFormationDesiredAnnualInterviews(): Collection
    {
        return $this->formationDesiredAnnualInterviews;
    }

    public function addFormationDesiredAnnualInterview(FormationDesiredAnnualInterview $formationDesiredAnnualInterview): self
    {
        if (!$this->formationDesiredAnnualInterviews->contains($formationDesiredAnnualInterview)) {
            $this->formationDesiredAnnualInterviews[] = $formationDesiredAnnualInterview;
            $formationDesiredAnnualInterview->setAnnualInterview($this);
        }

        return $this;
    }

    public function removeFormationDesiredAnnualInterview(FormationDesiredAnnualInterview $formationDesiredAnnualInterview): self
    {
        if ($this->formationDesiredAnnualInterviews->contains($formationDesiredAnnualInterview)) {
            $this->formationDesiredAnnualInterviews->removeElement($formationDesiredAnnualInterview);
            // set the owning side to null (unless already changed)
            if ($formationDesiredAnnualInterview->getAnnualInterview() === $this) {
                $formationDesiredAnnualInterview->setAnnualInterview(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FormationAnnualInterview[]
     */
    public function getFormationAnnualInterview(): Collection
    {
        return $this->formationAnnualInterview;
    }

    public function addFormationAnnualInterview(FormationAnnualInterview $formationAnnualInterview): self
    {
        if (!$this->formationAnnualInterview->contains($formationAnnualInterview)) {
            $this->formationAnnualInterview[] = $formationAnnualInterview;
            $formationAnnualInterview->setAnnualInterview($this);
        }

        return $this;
    }

    public function removeFormationAnnualInterview(FormationAnnualInterview $formationAnnualInterview): self
    {
        if ($this->formationAnnualInterview->contains($formationAnnualInterview)) {
            $this->formationAnnualInterview->removeElement($formationAnnualInterview);
            // set the owning side to null (unless already changed)
            if ($formationAnnualInterview->getAnnualInterview() === $this) {
                $formationAnnualInterview->setAnnualInterview(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GoalAnnualInterview[]
     */
    public function getGoalAnnualInterviews(): Collection
    {
        return $this->goalAnnualInterviews;
    }

    public function addGoalAnnualInterview(GoalAnnualInterview $goalAnnualInterview): self
    {
        if (!$this->goalAnnualInterviews->contains($goalAnnualInterview)) {
            $this->goalAnnualInterviews[] = $goalAnnualInterview;
            $goalAnnualInterview->setAnnualInterview($this);
        }

        return $this;
    }

    public function removeGoalAnnualInterview(GoalAnnualInterview $goalAnnualInterview): self
    {
        if ($this->goalAnnualInterviews->contains($goalAnnualInterview)) {
            $this->goalAnnualInterviews->removeElement($goalAnnualInterview);
            // set the owning side to null (unless already changed)
            if ($goalAnnualInterview->getAnnualInterview() === $this) {
                $goalAnnualInterview->setAnnualInterview(null);
            }
        }

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
}
