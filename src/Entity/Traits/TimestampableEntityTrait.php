<?php

namespace App\Entity\Traits;

trait TimestampableEntityTrait
{
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $created_at;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="update_at", type="datetime")
     */
    protected $updated_at;

    /**
     * Sets created_at.
     *
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt) : self
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()  : ?\DateTime
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)  : self
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt() : ?\DateTime
    {
        return $this->updated_at;
    }
}
