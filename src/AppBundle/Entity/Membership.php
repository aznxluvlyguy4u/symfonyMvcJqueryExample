<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="datetime")
     */
    protected $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="memberships")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    protected $company;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="memberships")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Card", inversedBy="memberships")
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id")
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
     * @ORM\JoinColumn(name="keys_form_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $keysForms;

    /**
     * @ORM\OneToMany(targetEntity="KvkExtract", mappedBy="membership", cascade={"persist"})
     * @ORM\JoinColumn(name="kvk_extract_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $kvkExtracts;

    /**
     * @ORM\OneToMany(targetEntity="DepositReceipt", mappedBy="membership", cascade={"persist"})
     * @ORM\JoinColumn(name="deposit_receipt_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
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
     * Set company
     *
     * @param \AppBundle\Entity\Company $company
     *
     * @return Membership
     */
    public function setCompany(\AppBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Membership
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
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
