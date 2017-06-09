<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DepositReceipt
 *
 * @ORM\Table(name="deposit_receipt")
 * @ORM\Entity(repositoryClass="BaseEntityRepository")
 */
class DepositReceipt extends Document
{
    /**
     * @ORM\ManyToOne(targetEntity="Membership", inversedBy="depositReceipts")
     */
    protected $membership;

    /**
     * Set membership
     *
     * @param \AppBundle\Entity\Membership $membership
     *
     * @return DepositReceipt
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
