<?php

namespace AppBundle\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Company;
use AppBundle\Entity\CompanyStatus;
use AppBundle\Entity\CompanyComment;
use AppBundle\Entity\CompanyStatusHistory;
use AppBundle\Form\CreateCompanyType;
use AppBundle\Form\EditCompanyType;
use AppBundle\Form\CreateCommentType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @Route("/company")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class CompanyController extends Controller
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('AppBundle:Company')->findBy(array('isDeleted' => false));

        return [
            'companies' => $companies,
        ];
    }


    /**
     * @Route("/create")
     * @Template
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

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_company_edit', ['company' => $company->getId()]);
            } else {
                return $this->redirectToRoute('app_company_index');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/{company}")
     * @Template
     */
    public function editAction(Request $request, Company $company)
    {
        $em = $this->getDoctrine()->getManager();

        $redirect = $request->query->get('redirect') ? $request->query->get('redirect') : 'app_company_index';
        $form = $this->createForm(EditCompanyType::class, $company, ['redirect' => $redirect]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // Calculate changeset on entity
            $uow = $em->getUnitOfWork();
            $uow->computeChangeSets();
            $changeset = $uow->getEntityChangeSet($company);

            if(array_key_exists('status', $changeset)) {
                list($previousStatus, $currentStatus) = $changeset['status'];

                $companyStatusHistory = new CompanyStatusHistory();
                $companyStatusHistory->setCompany($company);
                $companyStatusHistory->setPreviousStatus($previousStatus);
                $companyStatusHistory->setCurrentStatus($currentStatus);
                $companyStatusHistory->setCreatedBy($this->getUser());

                $companyComment = new CompanyComment();
                $companyComment->setCompany($company);
                $companyComment->setText('Changed status from <b>' . $previousStatus->getLabel() .  '</b> to <b>' . $currentStatus->getLabel() . '</b>.');
                $companyComment->setCreatedBy($this->getUser());

                $em->persist($companyStatusHistory);
                $em->persist($companyComment);
            }

            $em->persist($company);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_company_edit', ['company' => $company->getId(), 'redirect' => $redirect]);
            } else {
                return $this->redirectToRoute($form->get('redirect')->getData());
            }
        }

        $companyComment = new CompanyComment();
        $companyCommentForm = $this->createForm(CreateCommentType::class, $companyComment);

        $companyCommentForm->handleRequest($request);

        if ($companyCommentForm->isSubmitted() && $companyCommentForm->isValid()) {
            $companyComment->setCompany($company);
            $companyComment->setCreatedBy($this->getUser());
            $em->persist($companyComment);
            $em->flush();

            if ($companyCommentForm->get('save')->isClicked()) {
                return $this->redirectToRoute('app_company_edit', ['company' => $company->getId()]);
            } else {
                return $this->redirectToRoute('app_company_index');
            }
        }

        return [
            'form' => $form->createView(),
            'company' => $company,
            'companyCommentForm' => $companyCommentForm->createView(),
        ];
    }

    /**
     * @Route("/update/{company}/status/{status}", requirements={"company": "\d+", "status": "\d+"})
     * @Method("PUT")
     */
    public function updateStatusAction(Request $request, Company $company, CompanyStatus $status)
    {
        if($request->isXmlHttpRequest()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $previousStatus = $company->getStatus();
                $currentStatus = $status;

                $companyStatusHistory = new CompanyStatusHistory();
                $companyStatusHistory->setCompany($company);
                $companyStatusHistory->setPreviousStatus($previousStatus);
                $companyStatusHistory->setCurrentStatus($currentStatus);
                $companyStatusHistory->setCreatedBy($this->getUser());

                $companyComment = new CompanyComment();
                $companyComment->setCompany($company);
                $companyComment->setText('Changed status from <b>' . $previousStatus->getLabel() .  '</b> to <b>' . $currentStatus->getLabel() . '</b>.');
                $companyComment->setCreatedBy($this->getUser());

                $company->setStatus($currentStatus);

                $em->persist($companyStatusHistory);
                $em->persist($companyComment);
                $em->persist($company);
                $em->flush();

                return new JsonResponse(array(
                    'message' => 'OK'
                ), Response::HTTP_OK);
            } catch (Exception $e) {
                return new JsonResponse(array(
                    'error' => $e
                ), Response::CONFLICT);
            }
        }

        throw new BadRequestHttpException();
    }

    /**
     * Clears comments history.
     *
     * @Route("/delete/{company}/comments")
     * @Method("GET")
     * @Template
     */
    public function ClearCommentsAction(Request $request, Company $company)
    {
        $em = $this->getDoctrine()->getManager();

        foreach($company->getComments() as $comment) {
            $em->remove($comment);
        }

        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/delete/{company}")
     * @Template
     */
    public function deleteAction(Request $request, Company $company)
    {
        $em = $this->getDoctrine()->getManager();

        $company->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_company_index');
    }
}
