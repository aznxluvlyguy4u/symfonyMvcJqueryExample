<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 */
class CompanyStatus
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
     * @ORM\ManyToOne(targetEntity="EmailTemplate", inversedBy="companyStatuses")
     * @ORM\JoinColumn(name="email_template_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $emailTemplate;

    /**
     * @ORM\OneToMany(targetEntity="Company", mappedBy="status")
     */
    protected $companies;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
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
     * @return CompanyStatus
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
     * Add company
     *
     * @param \AppBundle\Entity\Company $company
     *
     * @return CompanyStatus
     */
    public function addCompany(\AppBundle\Entity\Company $company)
    {
        $this->companies[] = $company;

        return $this;
    }

    /**
     * Remove company
     *
     * @param \AppBundle\Entity\Company $company
     */
    public function removeCompany(\AppBundle\Entity\Company $company)
    {
        $this->companies->removeElement($company);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Set emailTemplate
     *
     * @param \AppBundle\Entity\EmailTemplate $emailTemplate
     *
     * @return CompanyStatus
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
