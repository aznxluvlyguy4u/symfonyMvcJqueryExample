<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * EmailTemplate
 *
 * @ORM\Table(name="email_template")
 * @ORM\Entity(repositoryClass="BaseEntityRepository")
 */
class EmailTemplate extends BaseEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=255)
     */
    private $body;

    /**
     * @ORM\OneToMany(targetEntity="CompanyStatus", mappedBy="status")
     */
    protected $companyStatuses;

    public function __construct()
    {
        $this->companyStatuses = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return EmailTemplate
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return EmailTemplate
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Add companyStatus
     *
     * @param \AppBundle\Entity\CompanyStatus $companyStatus
     *
     * @return EmailTemplate
     */
    public function addCompanyStatus(\AppBundle\Entity\CompanyStatus $companyStatus)
    {
        $this->companyStatuses[] = $companyStatus;

        return $this;
    }

    /**
     * Remove companyStatus
     *
     * @param \AppBundle\Entity\CompanyStatus $companyStatus
     */
    public function removeCompanyStatus(\AppBundle\Entity\CompanyStatus $companyStatus)
    {
        $this->companyStatuses->removeElement($companyStatus);
    }

    /**
     * Get companyStatuses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanyStatuses()
    {
        return $this->companyStatuses;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return EmailTemplate
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
     * @return EmailTemplate
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
     * @return EmailTemplate
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
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return EmailTemplate
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
