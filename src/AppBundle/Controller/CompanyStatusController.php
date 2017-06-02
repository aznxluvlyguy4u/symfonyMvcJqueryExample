<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CompanyStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Companystatus controller.
 *
 * @Route("companystatus")
 */
class CompanyStatusController extends Controller
{
    /**
     * Lists all companyStatus entities.
     *
     * @Route("/", name="companystatus_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companyStatuses = $em->getRepository('AppBundle:CompanyStatus')->findBy(array(), array('id' => 'ASC'));

        return $this->render('companystatus/index.html.twig', array(
            'companyStatuses' => $companyStatuses,
        ));
    }

    /**
     * Creates a new companyStatus entity.
     *
     * @Route("/new", name="companystatus_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $companyStatus = new Companystatus();
        $form = $this->createForm('AppBundle\Form\CompanyStatusType', $companyStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($companyStatus);
            $em->flush($companyStatus);

            return $this->redirectToRoute('companystatus_show', array('id' => $companyStatus->getId()));
        }

        return $this->render('companystatus/new.html.twig', array(
            'companyStatus' => $companyStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a companyStatus entity.
     *
     * @Route("/{id}", name="companystatus_show")
     * @Method("GET")
     */
    public function showAction(CompanyStatus $companyStatus)
    {
        $deleteForm = $this->createDeleteForm($companyStatus);

        return $this->render('companystatus/show.html.twig', array(
            'companyStatus' => $companyStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing companyStatus entity.
     *
     * @Route("/{id}/edit", name="companystatus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CompanyStatus $companyStatus)
    {
        $deleteForm = $this->createDeleteForm($companyStatus);
        $editForm = $this->createForm('AppBundle\Form\CompanyStatusType', $companyStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('companystatus_edit', array('id' => $companyStatus->getId()));
        }

        return $this->render('companystatus/edit.html.twig', array(
            'companyStatus' => $companyStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a companyStatus entity.
     *
     * @Route("/{id}", name="companystatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CompanyStatus $companyStatus)
    {
        $form = $this->createDeleteForm($companyStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($companyStatus);
            $em->flush($companyStatus);
        }

        return $this->redirectToRoute('companystatus_index');
    }

    /**
     * Creates a form to delete a companyStatus entity.
     *
     * @param CompanyStatus $companyStatus The companyStatus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CompanyStatus $companyStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('companystatus_delete', array('id' => $companyStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
