<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntityTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository")
 * @ORM\Table(name="histories")
 */
class History
{
    use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(name="message", type="json")
     */
    private $message = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="histories")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMessage(): ?array
    {
        return $this->message;
    }

    public function setMessage(array $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
