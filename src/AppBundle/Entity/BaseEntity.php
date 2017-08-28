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
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $modifiedAt;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    protected $isDeleted = false;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
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
