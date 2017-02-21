<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BaseEntityRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->queryAll()->getQuery()->getResult();
    }

    public function queryAll()
    {
        $qb = $this->createQueryBuilder('e');

        $qb
            ->where($qb->expr()->eq('e.isDeleted', ':isDeleted'))
            ->setParameter('isDeleted', false)
        ;

        return $qb;
    }
}
