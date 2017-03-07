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

        $people = $em->getRepository('AppBundle:Person')->findAll();

        return [
            'companies' => $companies,
            'people' => $people,
        ];
    }
}
