<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="BaseEntityRepository")
 */
class Company extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $contactFirstname;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $contactLastname;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $companyName;

    /**
     * @ORM\ManyToOne(targetEntity="CompanySector", inversedBy="companies")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     */
    protected $sector;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $numberOfEmployees;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $squareMetersWanted;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $zipcode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $websiteUrl;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $reference;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $offer;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $demand;

    /**
     * @ORM\OneToMany(targetEntity="CompanyComment", mappedBy="company")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $comments;

    /**
     * @ORM\ManyToOne(targetEntity="CompanyStatus", inversedBy="companies")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @ORM\OneToMany(targetEntity="CompanyStatusHistory", mappedBy="company")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $statusHistory;

    /**
     * @ORM\OneToMany(targetEntity="Membership", mappedBy="company")
     */
    protected $memberships;

    public function __construct()
    {
        $this->memberships = new ArrayCollection();
    }

    /**
     * Get CompanyStatus change date
     *
     * @return \DateTime
     */
    public function getStatusChangeDate()
    {
        if ($this->getStatusHistory()->isEmpty()) {
            return $this->getCreatedAt();
        } else {
            return $this->getStatusHistory()->first()->getCreatedAt();
        }
    }

    /**
     * Get CompanyStatus change date difference in days
     *
     * @return integer
     */
    public function getDiffStatusChangeInDays()
    {
        return $this->getStatusChangeDate()->diff(new \DateTime())->format('%a');
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
     * Set name
     *
     * @param string $name
     *
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set status
     *
     * @param \AppBundle\Entity\CompanyStatus $status
     *
     * @return Company
     */
    public function setStatus(\AppBundle\Entity\CompanyStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\CompanyStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Company
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
     * @return Company
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
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Company
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

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Company
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
     * Set email
     *
     * @param string $email
     *
     * @return Company
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Company
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\CompanyComment $comment
     *
     * @return Company
     */
    public function addComment(\AppBundle\Entity\CompanyComment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\CompanyComment $comment
     */
    public function removeComment(\AppBundle\Entity\CompanyComment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add companyStatusHistory
     *
     * @param \AppBundle\Entity\CompanyStatusHistory $companyStatusHistory
     *
     * @return Company
     */
    public function addCompanyStatusHistory(\AppBundle\Entity\CompanyStatusHistory $companyStatusHistory)
    {
        $this->companyStatusHistory[] = $companyStatusHistory;

        return $this;
    }

    /**
     * Remove companyStatusHistory
     *
     * @param \AppBundle\Entity\CompanyStatusHistory $companyStatusHistory
     */
    public function removeCompanyStatusHistory(\AppBundle\Entity\CompanyStatusHistory $companyStatusHistory)
    {
        $this->companyStatusHistory->removeElement($companyStatusHistory);
    }

    /**
     * Get companyStatusHistory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanyStatusHistory()
    {
        return $this->companyStatusHistory;
    }

    /**
     * Add statusHistory
     *
     * @param \AppBundle\Entity\CompanyStatusHistory $statusHistory
     *
     * @return Company
     */
    public function addStatusHistory(\AppBundle\Entity\CompanyStatusHistory $statusHistory)
    {
        $this->statusHistory[] = $statusHistory;

        return $this;
    }

    /**
     * Remove statusHistory
     *
     * @param \AppBundle\Entity\CompanyStatusHistory $statusHistory
     */
    public function removeStatusHistory(\AppBundle\Entity\CompanyStatusHistory $statusHistory)
    {
        $this->statusHistory->removeElement($statusHistory);
    }

    /**
     * Get statusHistory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatusHistory()
    {
        return $this->statusHistory;
    }

    /**
     * Set contactFirstname
     *
     * @param string $contactFirstname
     *
     * @return Company
     */
    public function setContactFirstname($contactFirstname)
    {
        $this->contactFirstname = $contactFirstname;

        return $this;
    }

    /**
     * Get contactFirstname
     *
     * @return string
     */
    public function getContactFirstname()
    {
        return $this->contactFirstname;
    }

    /**
     * Set contactLastname
     *
     * @param string $contactLastname
     *
     * @return Company
     */
    public function setContactLastname($contactLastname)
    {
        $this->contactLastname = $contactLastname;

        return $this;
    }

    /**
     * Get contactLastname
     *
     * @return string
     */
    public function getContactLastname()
    {
        return $this->contactLastname;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Company
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set numberOfEmployees
     *
     * @param integer $numberOfEmployees
     *
     * @return Company
     */
    public function setNumberOfEmployees($numberOfEmployees)
    {
        $this->numberOfEmployees = $numberOfEmployees;

        return $this;
    }

    /**
     * Get numberOfEmployees
     *
     * @return integer
     */
    public function getNumberOfEmployees()
    {
        return $this->numberOfEmployees;
    }

    /**
     * Set squareMetersWanted
     *
     * @param integer $squareMetersWanted
     *
     * @return Company
     */
    public function setSquareMetersWanted($squareMetersWanted)
    {
        $this->squareMetersWanted = $squareMetersWanted;

        return $this;
    }

    /**
     * Get squareMetersWanted
     *
     * @return integer
     */
    public function getSquareMetersWanted()
    {
        return $this->squareMetersWanted;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Company
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return Company
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Company
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set websiteUrl
     *
     * @param string $websiteUrl
     *
     * @return Company
     */
    public function setWebsiteUrl($websiteUrl)
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    /**
     * Get websiteUrl
     *
     * @return string
     */
    public function getWebsiteUrl()
    {
        return $this->websiteUrl;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Company
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set offer
     *
     * @param string $offer
     *
     * @return Company
     */
    public function setOffer($offer)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return string
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Set demand
     *
     * @param string $demand
     *
     * @return Company
     */
    public function setDemand($demand)
    {
        $this->demand = $demand;

        return $this;
    }

    /**
     * Get demand
     *
     * @return string
     */
    public function getDemand()
    {
        return $this->demand;
    }

    /**
     * Set sector
     *
     * @param \AppBundle\Entity\CompanySector $sector
     *
     * @return Company
     */
    public function setSector(\AppBundle\Entity\CompanySector $sector = null)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \AppBundle\Entity\CompanySector
     */
    public function getSector()
    {
        return $this->sector;
    }
}
