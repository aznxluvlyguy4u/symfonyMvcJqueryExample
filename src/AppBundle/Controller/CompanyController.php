<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Company;
use AppBundle\Form\CreateCompanyType;

/**
 * @Route("/company")
 */
class CompanyController extends Controller
{
    /**
     * @Route("/")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('AppBundle:Company')->findAll();

        return [
            'companies' => $companies,
        ];
    }


    /**
     * @Route("/create")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $company = new Company();
        $form = $this->createForm(CreateCompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('app_company_create');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
