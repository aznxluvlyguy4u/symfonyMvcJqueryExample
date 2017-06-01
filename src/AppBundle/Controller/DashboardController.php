<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\CompanyStatus;
use AppBundle\Entity\MembershipStatus;
use AppBundle\Entity\Company;
use AppBundle\Entity\Membership;
use AppBundle\Form\SendEmailType;
use Swift_Mailer;

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

        $status = $em->getRepository('AppBundle:CompanyStatus')->findOneByLabel('Contract signed');
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
        $companies = $em->getRepository(Company::class)->findCompaniesForFunnel();
        $memberships = $em->getRepository(Membership::class)->findMembershipsForFunnel();
        $emailForm = $this->createForm(SendEmailType::class);

        return [
            'companyStatuses' => $companyStatuses,
            'companies' => $companies,
            'membershipStatuses' => $membershipStatuses,
            'memberships' => $memberships,
            'emailForm' => $emailForm->createView(),
        ];
    }
}
