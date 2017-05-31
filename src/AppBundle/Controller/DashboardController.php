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

        /* @var Swift_Mailer $mailer */
        $mailer = $this->container->get('mailer');
        $message = new \Swift_Message();
        $message
            ->setSubject('test')
            ->setFrom($this->getParameter('mailer_source_address'))
            ->setTo('yubinchen18@gmail.com')
            ->setBody(
//                $this->renderView('AppBundle:Company:create.html.twig')
            );

        $mailer->send($message);



        return [
            'companyStatuses' => $companyStatuses,
            'companies' => $companies,
            'membershipStatuses' => $membershipStatuses,
            'memberships' => $memberships
        ];
    }
}
