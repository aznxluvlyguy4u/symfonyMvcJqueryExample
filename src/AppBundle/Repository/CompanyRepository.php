<?php

namespace AppBundle\Repository;

use AppBundle\Entity\CompanyStatus;

class CompanyRepository extends \Doctrine\ORM\EntityRepository
{
    public function findCompaniesForFunnel()
    {
        $em = $this->getEntityManager();
        $companyStatuses = $em->getRepository(CompanyStatus::class)->findAll();
        $results = $em->createQueryBuilder()
            ->select('c', 'COALESCE( MAX(csh.createdAt), c.createdAt) AS createdAt')
            ->from('AppBundle:Company', 'c')
            ->leftJoin('c.statusHistory', 'csh')
            ->where('c.isDeleted = :isDeleted')
            ->orderBy('createdAt', 'ASC')
            ->groupBy('c.id')
            ->setParameter('isDeleted', false)
            ->getQuery()
            ->getResult();
        ;

        $companies = [];
        foreach ($companyStatuses as $companyStatus) {
            $companies[$companyStatus->getLabel()] = [];
        }

        foreach ($results as $result) {
            $companies[$result[0]->getStatus()->getLabel()][] = $result[0];
        }
        return $companies;
    }
}
