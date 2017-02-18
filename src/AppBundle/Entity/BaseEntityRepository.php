<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BaseEntityRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(['isDeleted' => 0]);
    }
}
