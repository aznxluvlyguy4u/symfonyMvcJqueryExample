<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MembershipStatusHistory extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Membership", inversedBy="statusHistory")
     */
    protected $membership;

    /**
     * @ORM\ManyToOne(targetEntity="MembershipStatus")
     */
    protected $previousStatus;

    /**
     * @ORM\ManyToOne(targetEntity="MembershipStatus")
     */
    protected $currentStatus;

    public function __construct()
    {
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MembershipStatusHistory
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return MembershipStatusHistory
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return MembershipStatusHistory
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set membership
     *
     * @param \AppBundle\Entity\Membership $membership
     *
     * @return MembershipStatusHistory
     */
    public function setMembership(\AppBundle\Entity\Membership $membership = null)
    {
        $this->membership = $membership;

        return $this;
    }

    /**
     * Get membership
     *
     * @return \AppBundle\Entity\Membership
     */
    public function getMembership()
    {
        return $this->membership;
    }

    /**
     * Set previousStatus
     *
     * @param \AppBundle\Entity\MembershipStatus $previousStatus
     *
     * @return MembershipStatusHistory
     */
    public function setPreviousStatus(\AppBundle\Entity\MembershipStatus $previousStatus = null)
    {
        $this->previousStatus = $previousStatus;

        return $this;
    }

    /**
     * Get previousStatus
     *
     * @return \AppBundle\Entity\MembershipStatus
     */
    public function getPreviousStatus()
    {
        return $this->previousStatus;
    }

    /**
     * Set currentStatus
     *
     * @param \AppBundle\Entity\MembershipStatus $currentStatus
     *
     * @return MembershipStatusHistory
     */
    public function setCurrentStatus(\AppBundle\Entity\MembershipStatus $currentStatus = null)
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    /**
     * Get currentStatus
     *
     * @return \AppBundle\Entity\MembershipStatus
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return MembershipStatusHistory
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
