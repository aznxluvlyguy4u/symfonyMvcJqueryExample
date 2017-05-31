<?php

namespace AppBundle\Repository;

use AppBundle\Entity\MembershipStatus;

class MembershipRepository extends \Doctrine\ORM\EntityRepository
{
    public function findMembershipsForFunnel()
    {
        $em = $this->getEntityManager();
        $membershipStatuses = $em->getRepository(MembershipStatus::class)->findAll();
        $results = $em->createQueryBuilder()
            ->select('c', 'COALESCE( MAX(csh.createdAt), c.createdAt) AS createdAt')
            ->from('AppBundle:Membership', 'c')
            ->leftJoin('c.statusHistory', 'csh')
            ->where('c.isDeleted = :isDeleted')
            ->orderBy('createdAt', 'ASC')
            ->groupBy('c.id')
            ->setParameter('isDeleted', false)
            ->getQuery()
            ->getResult();
        ;

        $memberships = [];
        foreach ($membershipStatuses as $membershipStatus) {
            $memberships[$membershipStatus->getLabel()] = [];
        }

        foreach ($results as $result) {
            $memberships[$result[0]->getStatus()->getLabel()][] = $result[0];
        }
        return $memberships;
    }
}
