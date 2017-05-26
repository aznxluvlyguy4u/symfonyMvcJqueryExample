<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table("fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Membership", mappedBy="user")
     */
    protected $memberships;

    public function __construct()
    {
        parent::__construct();
        $this->memberships = new ArrayCollection();
    }

    /**
     * @ORM\OneToOne(targetEntity="Profile")
     */
    protected $profile;

    /**
     * Set profile
     *
     * @param \AppBundle\Entity\Profile $profile
     *
     * @return User
     */
    public function setProfile(\AppBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \AppBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
