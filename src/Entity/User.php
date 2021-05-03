<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Traits\TimestampableEntityTrait;
use App\Entity\Traits\SoftdeleteableEntityTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users", indexes={
 *      @ORM\Index(columns={"deleted_at"})
 * })
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false)
 */
class User implements UserInterface, \Serializable
{
    use TimestampableEntityTrait;
    use SoftdeleteableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var array
     * @ORM\Column(name="roles", type="simple_array", length=255, nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="first_name", type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(name="date_entered", type="date", nullable=true)
     */
    private $dateEntered;

    /**
     * @ORM\Column(name="cgu_accepted", type="datetime", nullable=true)
     */
    private $cguAccepted;

    /**
     * @ORM\Column(name="azure_id", type="text", nullable=true)
     */
    private $azureId;

    /**
     * @ORM\Column(name="azure_access_token", type="text", nullable=true)
     */
    private $azureAccessToken;

    /**
     * @ORM\Column(name="last_connection", type="datetime", nullable=true)
     */
    private $lastConnection;

    /**
     * @ORM\Column(name="location", type="string", nullable=true)
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProInterview", mappedBy="employee", cascade={"remove"}, fetch="EXTRA_LAZY")
     */
    private $proInterviews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AnnualInterview", mappedBy="employee", cascade={"remove"}, fetch="EXTRA_LAZY")
     */
    private $annualInterviews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Manager", mappedBy="employee", cascade={"remove"}, fetch="EXTRA_LAZY")
     */
    private $manager;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Manager", mappedBy="manager", cascade={"remove"}, fetch="EXTRA_LAZY")
     */
    private $employee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\History", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $histories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Working", mappedBy="employee", cascade={"remove"}, fetch="EXTRA_LAZY")
     */
    private $workings;

    public function __construct()
    {
        $this->proInterviews = new ArrayCollection();
        $this->annualInterviews = new ArrayCollection();
        $this->manager = new ArrayCollection();
        $this->employee = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->workings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return array
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // Every user at least has ROLE_USER
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return '';
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
        ]);
        // T;ODO: Implement serialize() method.
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            ) = unserialize($serialized, ['allowed_classes', false]);
        // TODO: Implement unserialize() method.
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getDateEntered(): ?\DateTimeInterface
    {
        return $this->dateEntered;
    }

    public function setDateEntered(?\DateTimeInterface $dateEntered): self
    {
        $this->dateEntered = $dateEntered;

        return $this;
    }

    /**
     * @return Collection|ProInterview[]
     */
    public function getProInterviews(): Collection
    {
        return $this->proInterviews;
    }

    public function addProInterview(ProInterview $proInterview): self
    {
        if (!$this->proInterviews->contains($proInterview)) {
            $this->proInterviews[] = $proInterview;
            $proInterview->setUser($this);
        }

        return $this;
    }

    public function removeProInterview(ProInterview $proInterview): self
    {
        if ($this->proInterviews->contains($proInterview)) {
            $this->proInterviews->removeElement($proInterview);
            // set the owning side to null (unless already changed)
            if ($proInterview->getUser() === $this) {
                $proInterview->setUser(null);
            }
        }

        return $this;
    }

    public function getFullName() : string
    {
        return ucfirst($this->lastName) . ' ' . ucfirst($this->firstName);
    }

    public function __toString() : string
    {
        return $this->getFullName();
    }

    /**
     * @return Collection|AnnualInterview[]
     */
    public function getAnnualInterviews(): Collection
    {
        return $this->annualInterviews;
    }

    public function addAnnualInterview(AnnualInterview $annualInterview): self
    {
        if (!$this->annualInterviews->contains($annualInterview)) {
            $this->annualInterviews[] = $annualInterview;
            $annualInterview->setEmployee($this);
        }

        return $this;
    }

    public function removeAnnualInterview(AnnualInterview $annualInterview): self
    {
        if ($this->annualInterviews->contains($annualInterview)) {
            $this->annualInterviews->removeElement($annualInterview);
            // set the owning side to null (unless already changed)
            if ($annualInterview->getEmployee() === $this) {
                $annualInterview->setEmployee(null);
            }
        }

        return $this;
    }

    public function isAdmin() : bool
    {
        return in_array('ROLE_ADMIN', $this->roles);
    }

    public function isManager() : bool
    {
        return count($this->employee) > 0;
    }

    public function getCguAccepted(): ?\DateTimeInterface
    {
        return $this->cguAccepted;
    }

    public function setCguAccepted(?\DateTimeInterface $cguAccepted): self
    {
        $this->cguAccepted = $cguAccepted;

        return $this;
    }

    public function getAzureId(): ?string
    {
        return $this->azureId;
    }

    public function setAzureId(?string $azureId): self
    {
        $this->azureId = $azureId;

        return $this;
    }

    public function getAzureAccessToken(): ?string
    {
        return $this->azureAccessToken;
    }

    public function setAzureAccessToken(?string $azureAccessToken): self
    {
        $this->azureAccessToken = $azureAccessToken;

        return $this;
    }

    public function getLastConnection(): ?\DateTimeInterface
    {
        return $this->lastConnection;
    }

    public function setLastConnection(?\DateTimeInterface $lastConnection): self
    {
        $this->lastConnection = $lastConnection;

        return $this;
    }

    public function anonymize()
    {
        // OLD_ avec premiere lettre du prenom, premiere lettre du nom et derniere lettre du nom
        $name = strtoupper(
            'OLD_' .
            substr($this->firstName, 0, 1) .
            substr($this->lastName, 0, 1) .
            substr($this->lastName, -1)
        );

        $this->email = 'anonyme' . $this->id . '@anonyme.anonyme';
        $this->lastName = $name;
        $this->firstName = $name;
        $this->dateEntered = null;
        $this->birthday = null;
        $this->roles = ['ROLE_USER'];
        $this->azureId = null;
        $this->azureAccessToken = null;

        return $this;
    }

    /**
     * @return Collection|Manager[]
     */
    public function getManager(): Collection
    {
        return $this->manager;
    }

    public function addManager(Manager $manager): self
    {
        if (!$this->manager->contains($manager)) {
            $this->manager[] = $manager;
            $manager->setEmployee($this);
        }

        return $this;
    }

    public function removeManager(Manager $manager): self
    {
        if ($this->manager->contains($manager)) {
            $this->manager->removeElement($manager);
            // set the owning side to null (unless already changed)
            if ($manager->getEmployee() === $this) {
                $manager->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Manager[]
     */
    public function getEmployee(): Collection
    {
        return $this->employee;
    }

    public function addEmployee(Manager $employee): self
    {
        if (!$this->employee->contains($employee)) {
            $this->employee[] = $employee;
            $employee->setManager($this);
        }

        return $this;
    }

    public function removeEmployee(Manager $employee): self
    {
        if ($this->employee->contains($employee)) {
            $this->employee->removeElement($employee);
            // set the owning side to null (unless already changed)
            if ($employee->getManager() === $this) {
                $employee->setManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setUser($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getUser() === $this) {
                $history->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Working[]
     */
    public function getWorkings(): Collection
    {
        return $this->workings;
    }

    public function addWorking(Working $working): self
    {
        if (!$this->workings->contains($working)) {
            $this->workings[] = $working;
            $working->setEmployee($this);
        }

        return $this;
    }

    public function removeWorking(Working $working): self
    {
        if ($this->workings->contains($working)) {
            $this->workings->removeElement($working);
            // set the owning side to null (unless already changed)
            if ($working->getEmployee() === $this) {
                $working->setEmployee(null);
            }
        }

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

}
