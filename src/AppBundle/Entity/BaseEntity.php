<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class BaseEntity
{
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $modifiedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDeleted = false;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $createdBy;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedDate()
    {
        $this->createdAt = new \DateTime();
        $this->modifiedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setModifiedDate()
    {
        $this->modifiedAt = new \DateTime();
    }
    
    /**
     * Get the comment object type
     * @return string
     */
    public function getClassName()
    {
        return (new ReflectionClass($this))->getShortName();
    }
}
