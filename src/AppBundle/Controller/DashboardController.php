<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
        
        $companyStatuses = $em->getRepository('AppBundle:CompanyStatus')->findAll();
        $companies = $em->getRepository('AppBundle:Company')->findAll();

        return [
            'companyStatuses' => $companyStatuses,
            'companies' => $companies,
        ];
    }
}
