<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CompanyStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Companystatus controller.
 *
 *
 * @Route("/configuration/companystatus")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class CompanyStatusController extends Controller
{
    /**
     * Lists all companyStatus entities.
     *
     * @Route("/")
     * @Template
     * @Method("GET")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $companyStatuses = $em->getRepository('AppBundle:CompanyStatus')->findBy(array(), array('position' => 'ASC'));

        return [
            'companyStatuses' => $companyStatuses,
        ];
    }

    /**
     * Creates a new companyStatus entity.
     *
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function createAction(Request $request)
    {
        $companyStatus = new Companystatus();
        $form = $this->createForm('AppBundle\Form\CompanyStatusType', $companyStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($companyStatus);
            $em->flush($companyStatus);

            return $this->redirectToRoute('app_companystatus_edit', array('id' => $companyStatus->getId()));
        }

        return [
            'companyStatus' => $companyStatus,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing companyStatus entity.
     *
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function editAction(Request $request, CompanyStatus $companyStatus)
    {
        //$deleteForm = $this->createDeleteForm($companyStatus);
        $editForm = $this->createForm('AppBundle\Form\CompanyStatusType', $companyStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_companystatus_edit', array('id' => $companyStatus->getId()));
        }

        return [
            'companyStatus' => $companyStatus,
            'form' => $editForm->createView(),
        ];
    }

    /**
     * Resorts an item using it's doctrine sortable property
     * @param integer $id
     * @param integer $position
     * @Route("/sort/{id}/{position}")
     * @Template
     * @Method("GET")
     */
    public function sortAction($id, $position)
    {
        $em = $this->getDoctrine()->getManager();
        $companyStatus = $em->getRepository('AppBundle:CompanyStatus')->find($id);
        $companyStatus->setPosition($position);
        $em->persist($companyStatus);
        $em->flush();
        $request = new Request();
        return $this->indexAction($request);
    }

    /**
     * Deletes a companyStatus entity.
     *
     * @Route("/delete/{id}")
     * @Template
     */
    public function deleteAction(Request $request, CompanyStatus $companyStatus)
    {

        $em = $this->getDoctrine()->getManager();

        $companyStatus->setPosition(-1);
        $companyStatus->setIsDeleted(true);
        $em->persist($companyStatus);

        $em->flush();

        return $this->redirectToRoute('app_companystatus_index');
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
            ->setAction($this->generateUrl('app_companystatus_delete', array('id' => $companyStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
