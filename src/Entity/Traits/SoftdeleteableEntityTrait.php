<?php

namespace App\Entity\Traits;

trait SoftdeleteableEntityTrait
{
    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deleted_at;

    /**
     * @param \DateTime $deletedAt
     * @return $this
     */
    public function setDeletedAt(\DateTime $deletedAt) : self
    {
        $this->deleted_at = $deletedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt() : ?\DateTime
    {
        return $this->deleted_at;
    }

    /**
     * @return bool
     */
    public function isDeleted() : bool
    {
        return $this->deleted_at !== null;
    }
}