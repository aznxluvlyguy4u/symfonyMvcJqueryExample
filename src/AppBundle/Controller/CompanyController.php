<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Company;
use AppBundle\Entity\CompanyComment;
use AppBundle\Form\CreateCompanyType;
use AppBundle\Form\EditCompanyType;
use AppBundle\Form\CreateCompanyCommentType;

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

        if ($form->isSubmitted() && $form->isValid()) {
            $company->setCreatedBy($this->getUser());
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('app_company_edit', ['company' => $company->getId()]);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/{company}")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function editAction(Request $request, Company $company)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(EditCompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('app_company_edit', ['company' => $company->getId()]);
        }

        $companyComment = new CompanyComment();
        $companyCommentForm = $this->createForm(CreateCompanyCommentType::class, $companyComment);

        $companyCommentForm->handleRequest($request);

        if($companyCommentForm->isSubmitted() && $companyCommentForm->isValid())
        {
            $companyComment->setCompany($company);
            $companyComment->setCreatedBy($this->getUser());
            $em->persist($companyComment);
            $em->flush();

            return $this->redirectToRoute('app_company_edit', ['company' => $company->getId()]);
        }

        return [
            'form' => $form->createView(),
            'company' => $company,
            'companyCommentForm' => $companyCommentForm->createView(),
        ];
    }

    /**
     * @Route("/delete/{company}")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Request $request, Company $company)
    {
        $em = $this->getDoctrine()->getManager();

        $company->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_company_index');
    }
}
