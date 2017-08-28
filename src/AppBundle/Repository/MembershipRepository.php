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
            ->select('m', 'COALESCE( MAX(msh.createdAt), m.createdAt) AS createdAt')
            ->from('AppBundle:Membership', 'm')
            ->leftJoin('m.statusHistory', 'msh')
            ->where('m.isDeleted = :isDeleted')
            ->orderBy('createdAt', 'ASC')
            ->groupBy('m.id')
            ->setParameter('isDeleted', false)
            ->getQuery()
            ->getResult();
        ;

        $memberships = [];
        foreach ($membershipStatuses as $membershipStatus) {
            $memberships[$membershipStatus->getLabel()] = [];
        }

        foreach ($results as $result) {

            if($result[0]->getStatus()) {
                $memberships[$result[0]->getStatus()->getLabel()][] = $result[0];
            }
        }
        return $memberships;
    }

    public function findMembershipWithDocuments($membershipId)
    {
        $em = $this->getEntityManager();
        $result = $em->createQueryBuilder()
            ->select('m', 'mcd')
            ->from('AppBundle:Membership', 'm')
            ->join('m.contractDocs', 'mcd')
//            ->join('m.SepaForm', 'msf')
//            ->join('m.KeysForm', 'mkf')
//            ->join('m.KvkExtract', 'mke')
//            ->join('m.DepositReceipt', 'mdr')
            ->where('m.id = :mid')
            ->andWhere('mcd.isDeleted = :isDeleted')
//            ->andWhere('msf.isDeleted = :isDeleted')
//            ->andWhere('mkf.isDeleted = :isDeleted')
//            ->andWhere('mke.isDeleted = :isDeleted')
//            ->andWhere('mdr.isDeleted = :isDeleted')
            ->setParameters(array('mid' => $membershipId, 'isDeleted' => false))
            ->getQuery()
            ->getResult();

        return $result;
    }
}
