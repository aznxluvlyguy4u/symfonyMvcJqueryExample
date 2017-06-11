<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContractDoc
 *
 * @ORM\Table(name="contract_doc")
 * @ORM\Entity(repositoryClass="BaseEntityRepository")
 */
class ContractDoc extends Document
{
    /**
     * @ORM\ManyToOne(targetEntity="Membership", inversedBy="contractDocs")
     */
    protected $membership;

    /**
     * Set membership
     *
     * @param \AppBundle\Entity\Membership $membership
     *
     * @return ContractDoc
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
