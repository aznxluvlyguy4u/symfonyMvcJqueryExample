<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MembershipRepository")
 */
class Membership extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endDate;

    /**
     * @ORM\ManyToMany(targetEntity="Company", inversedBy="memberships")
     * @@ORM\JoinTable(name="membership_companies")
     */
    protected $companies;

    /**
     * @ORM\OneToOne(targetEntity="Card", mappedBy="membership")
     */
    protected $card;

    /**
     * @ORM\OneToMany(targetEntity="MembershipComment", mappedBy="membership")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $comments;
    
    /**
     * @ORM\ManyToOne(targetEntity="MembershipStatus", inversedBy="memberships")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $newsletter;

    /**
     * @ORM\OneToMany(targetEntity="MembershipStatusHistory", mappedBy="membership")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $statusHistory;

    /**
     * @ORM\OneToMany(targetEntity="ContractDoc", mappedBy="membership", cascade={"persist"})
     */
    protected $contractDocs;

    /**
     * @ORM\OneToMany(targetEntity="SepaForm", mappedBy="membership", cascade={"persist"})
     */
    protected $sepaForms;

    /**
     * @ORM\OneToMany(targetEntity="KeysForm", mappedBy="membership", cascade={"persist"})
     */
    protected $keysForms;

    /**
     * @ORM\OneToMany(targetEntity="KvkExtract", mappedBy="membership", cascade={"persist"})
     */
    protected $kvkExtracts;

    /**
     * @ORM\OneToMany(targetEntity="DepositReceipt", mappedBy="membership", cascade={"persist"})
     */
    protected $depositReceipts;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->statusHistory = new ArrayCollection();
        $this->contractDocs = new ArrayCollection();
        $this->sepaForms = new ArrayCollection();
        $this->keysForms = new ArrayCollection();
        $this->kvkExtracts = new ArrayCollection();
        $this->depositReceipts = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @param mixed $newsletter
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * Get MembershipStatus change date
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
     * Get MembershipStatus change date difference in days
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Membership
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Membership
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Membership
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
     * @return Membership
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
     * @return Membership
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
     * @return mixed
     */
    public function getCompany()
    {
        return $this->companies;
    }

    /**
     * @param mixed $companies
     */
    public function setCompany($companies)
    {
        $this->companies = $companies;
    }

    /**
     * Set card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return Membership
     */
    public function setCard(\AppBundle\Entity\Card $card = null)
    {
        $this->card = $card;
        $card->setMembership($this);

        return $this;
    }

    /**
     * Get card
     *
     * @return \AppBundle\Entity\Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @return mixed
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @param mixed $companies
     */
    public function setCompanies($companies)
    {
        $this->companies = $companies;
    }

    /**
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Membership
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
     * Set status
     *
     * @param \AppBundle\Entity\MembershipStatus $status
     *
     * @return Membership
     */
    public function setStatus(\AppBundle\Entity\MembershipStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\MembershipStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add statusHistory
     *
     * @param \AppBundle\Entity\MembershipStatusHistory $statusHistory
     *
     * @return Membership
     */
    public function addStatusHistory(\AppBundle\Entity\MembershipStatusHistory $statusHistory)
    {
        $this->statusHistory[] = $statusHistory;

        return $this;
    }

    /**
     * Remove statusHistory
     *
     * @param \AppBundle\Entity\MembershipStatusHistory $statusHistory
     */
    public function removeStatusHistory(\AppBundle\Entity\MembershipStatusHistory $statusHistory)
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
     * Add comment
     *
     * @param \AppBundle\Entity\MembershipComment $comment
     *
     * @return Membership
     */
    public function addComment(\AppBundle\Entity\MembershipComment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\MembershipComment $comment
     */
    public function removeComment(\AppBundle\Entity\MembershipComment $comment)
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
     * Add contractDoc
     *
     * @param \AppBundle\Entity\ContractDoc $contractDoc
     *
     * @return Membership
     */
    public function addContractDoc(\AppBundle\Entity\ContractDoc $contractDoc)
    {
        $contractDoc->setMembership($this);
        $this->contractDocs[] = $contractDoc;

        return $this;
    }

    /**
     * Remove contractDoc
     *
     * @param \AppBundle\Entity\ContractDoc $contractDoc
     */
    public function removeContractDoc(\AppBundle\Entity\ContractDoc $contractDoc)
    {
        $contractDoc->setIsDeleted(true);
        $this->contractDocs->removeElement($contractDoc);
    }

    /**
     * Get contractDocs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContractDocs()
    {
        return $this->contractDocs;
    }

    /**
     * Add sepaForm
     *
     * @param \AppBundle\Entity\SepaForm $sepaForm
     *
     * @return Membership
     */
    public function addSepaForm(\AppBundle\Entity\SepaForm $sepaForm)
    {
        $sepaForm->setMembership($this);
        $this->sepaForms[] = $sepaForm;

        return $this;
    }

    /**
     * Remove sepaForm
     *
     * @param \AppBundle\Entity\SepaForm $sepaForm
     */
    public function removeSepaForm(\AppBundle\Entity\SepaForm $sepaForm)
    {
        $sepaForm->setIsDeleted(true);
        $this->sepaForms->removeElement($sepaForm);
    }

    /**
     * Get sepaForms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSepaForms()
    {
        return $this->sepaForms;
    }

    /**
     * Add keysForm
     *
     * @param \AppBundle\Entity\KeysForm $keysForm
     *
     * @return Membership
     */
    public function addKeysForm(\AppBundle\Entity\KeysForm $keysForm)
    {
        $keysForm->setMembership($this);
        $this->keysForms[] = $keysForm;

        return $this;
    }

    /**
     * Remove keysForm
     *
     * @param \AppBundle\Entity\KeysForm $keysForm
     */
    public function removeKeysForm(\AppBundle\Entity\KeysForm $keysForm)
    {
        $keysForm->setIsDeleted(true);
        $this->keysForms->removeElement($keysForm);
    }

    /**
     * Get keysForms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKeysForms()
    {
        return $this->keysForms;
    }

    /**
     * Add kvkExtract
     *
     * @param \AppBundle\Entity\KvkExtract $kvkExtract
     *
     * @return Membership
     */
    public function addKvkExtract(\AppBundle\Entity\KvkExtract $kvkExtract)
    {
        $kvkExtract->setMembership($this);
        $this->kvkExtracts[] = $kvkExtract;

        return $this;
    }

    /**
     * Remove kvkExtract
     *
     * @param \AppBundle\Entity\KvkExtract $kvkExtract
     */
    public function removeKvkExtract(\AppBundle\Entity\KvkExtract $kvkExtract)
    {
        $kvkExtract->setIsDeleted(true);
        $this->kvkExtracts->removeElement($kvkExtract);
    }

    /**
     * Get kvkExtracts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKvkExtracts()
    {
        return $this->kvkExtracts;
    }

    /**
     * Add depositReceipt
     *
     * @param \AppBundle\Entity\DepositReceipt $depositReceipt
     *
     * @return Membership
     */
    public function addDepositReceipt(\AppBundle\Entity\DepositReceipt $depositReceipt)
    {
        $depositReceipt->setMembership($this);
        $this->depositReceipts[] = $depositReceipt;

        return $this;
    }

    /**
     * Remove depositReceipt
     *
     * @param \AppBundle\Entity\DepositReceipt $depositReceipt
     */
    public function removeDepositReceipt(\AppBundle\Entity\DepositReceipt $depositReceipt)
    {
        $depositReceipt->setIsDeleted(true);
        $this->depositReceipts->removeElement($depositReceipt);
    }

    /**
     * Get depositReceipts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepositReceipts()
    {
        return $this->depositReceipts;
    }
}
