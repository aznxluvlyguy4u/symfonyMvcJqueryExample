<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SepaForm
 *
 * @ORM\Table(name="sepa_form")
 * @ORM\Entity(repositoryClass="BaseEntityRepository")
 */
class SepaForm extends Document
{
    /**
     * @ORM\ManyToOne(targetEntity="Membership", inversedBy="sepaForms")
     * @ORM\JoinColumn(name="membership_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $membership;

    /**
     * Set membership
     *
     * @param \AppBundle\Entity\Membership $membership
     *
     * @return SepaForm
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
}
