<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KeysForm
 *
 * @ORM\Table(name="keys_form")
 * @ORM\Entity(repositoryClass="BaseEntityRepository")
 */
class KeysForm extends Document
{
    /**
     * @ORM\ManyToOne(targetEntity="Membership", inversedBy="keysForms")
     */
    protected $membership;

    /**
     * Set membership
     *
     * @param \AppBundle\Entity\Membership $membership
     *
     * @return KeysForm
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
