<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class MembershipStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $label;
    
    /**
     * @ORM\ManyToOne(targetEntity="EmailTemplate", inversedBy="membershipStatuses")
     * @ORM\JoinColumn(name="email_template_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $emailTemplate;

    /**
     * @ORM\OneToMany(targetEntity="Membership", mappedBy="status")
     */
    protected $memberships;

    public function __construct()
    {
        $this->memberships = new ArrayCollection();
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
     * Set label
     *
     * @param string $label
     *
     * @return MembershipStatus
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Add membership
     *
     * @param \AppBundle\Entity\Membership $membership
     *
     * @return MembershipStatus
     */
    public function addMembership(\AppBundle\Entity\Membership $membership)
    {
        $this->memberships[] = $membership;

        return $this;
    }

    /**
     * Remove membership
     *
     * @param \AppBundle\Entity\Membership $membership
     */
    public function removeMembership(\AppBundle\Entity\Membership $membership)
    {
        $this->memberships->removeElement($membership);
    }

    /**
     * Get memberships
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMemberships()
    {
        return $this->memberships;
    }

    /**
     * Set emailTemplate
     *
     * @param \AppBundle\Entity\EmailTemplate $emailTemplate
     *
     * @return MembershipStatus
     */
    public function setEmailTemplate(\AppBundle\Entity\EmailTemplate $emailTemplate = null)
    {
        $this->emailTemplate = $emailTemplate;

        return $this;
    }

    /**
     * Get emailTemplate
     *
     * @return \AppBundle\Entity\EmailTemplate
     */
    public function getEmailTemplate()
    {
        return $this->emailTemplate;
    }
}
