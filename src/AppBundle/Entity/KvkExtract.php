<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KvkExtract
 *
 * @ORM\Table(name="kvk_extract")
 * @ORM\Entity(repositoryClass="BaseEntityRepository")
 */
class KvkExtract extends Document
{
    /**
     * @ORM\ManyToOne(targetEntity="Membership", inversedBy="kvkExtracts")
     */
    protected $membership;

    /**
     * Set membership
     *
     * @param \AppBundle\Entity\Membership $membership
     *
     * @return KvkExtract
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
