<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\CompanyStatus;
use AppBundle\Entity\MembershipStatus;

/**
 * @Route("/")
 * @Security("is_granted('ROLE_USER')")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $status = $em->getRepository('AppBundle:CompanyStatus')->findOneByLabel('member');
        $companies = $em->getRepository('AppBundle:Company')->findBy(['isDeleted' => false, 'status' => $status]);

        $profiles = $em->getRepository('AppBundle:Profile')->findAll();

        $comments = $em->getRepository('AppBundle:Comment')->findBy([], ['createdAt' => 'DESC'], 5);

        return [
            'companies' => $companies,
            'profiles' => $profiles,
            'comments' => $comments,
        ];
    }

    /**
     * @Route("/funnel")
     * @Template
     */
    public function funnelAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companyStatuses = $em->getRepository(CompanyStatus::class)->findAll();
        $membershipStatuses = $em->getRepository(MembershipStatus::class)->findAll();

        $qb = $em->createQueryBuilder();

        $qb
            ->select('c', 'COALESCE( MAX(csh.createdAt), c.createdAt) AS createdAt')
            ->from('AppBundle:Company', 'c')
            ->leftJoin('c.statusHistory', 'csh')
            ->where('c.isDeleted = :isDeleted')
            ->orderBy('createdAt', 'ASC')
            ->groupBy('c.id')
            ->setParameter('isDeleted', false)
        ;

        $results = $qb->getQuery()->getResult();

        $companies = [];
        foreach ($companyStatuses as $companyStatus) {
            $companies[$companyStatus->getLabel()] = [];
        }

        foreach ($results as $result) {
            $companies[$result[0]->getStatus()->getLabel()][] = $result[0];
        }

        // $companies = $em->getRepository('AppBundle:Company')->findAll();

        // get memberships
        $qb
            ->select('m', 'COALESCE( MAX(msh.createdAt), m.createdAt) AS createdAt')
            ->from('AppBundle:Membership', 'm')
            ->leftJoin('m.statusHistory', 'msh')
            ->where('m.isDeleted = :isDeleted')
            ->orderBy('createdAt', 'ASC')
            ->groupBy('m.id')
            ->setParameter('isDeleted', false)
        ;

        $membershipResults = $qb->getQuery()->getResult();
        $memberships = [];
        foreach ($membershipStatuses as $membershipStatus) {
            $memberships[$membershipStatus->getLabel()] = [];
        }

        foreach ($membershipResults as $membershipResult) {
            $memberships[$membershipResult[0]->getStatus()->getLabel()][] = $membershipResult[0];
        }


        return [
            'companyStatuses' => $companyStatuses,
            'companies' => $companies,
            'membershipStatuses' => $membershipStatuses,
            'memberships' => $memberships
        ];
    }
}
