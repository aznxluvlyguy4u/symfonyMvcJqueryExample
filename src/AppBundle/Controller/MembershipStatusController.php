<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MembershipStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Membershipstatus controller.
 *
 * @Route("membershipstatus")
 */
class MembershipStatusController extends Controller
{
    /**
     * Lists all membershipStatus entities.
     *
     * @Route("/", name="membershipstatus_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $membershipStatuses = $em->getRepository('AppBundle:MembershipStatus')->findBy(array(), array('id' => 'ASC'));

        return $this->render('membershipstatus/index.html.twig', array(
            'membershipStatuses' => $membershipStatuses,
        ));
    }

    /**
     * Creates a new membershipStatus entity.
     *
     * @Route("/new", name="membershipstatus_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $membershipStatus = new Membershipstatus();
        $form = $this->createForm('AppBundle\Form\MembershipStatusType', $membershipStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membershipStatus);
            $em->flush($membershipStatus);

            return $this->redirectToRoute('membershipstatus_show', array('id' => $membershipStatus->getId()));
        }

        return $this->render('membershipstatus/new.html.twig', array(
            'membershipStatus' => $membershipStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a membershipStatus entity.
     *
     * @Route("/{id}", name="membershipstatus_show")
     * @Method("GET")
     */
    public function showAction(MembershipStatus $membershipStatus)
    {
        $deleteForm = $this->createDeleteForm($membershipStatus);

        return $this->render('membershipstatus/show.html.twig', array(
            'membershipStatus' => $membershipStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing membershipStatus entity.
     *
     * @Route("/{id}/edit", name="membershipstatus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MembershipStatus $membershipStatus)
    {
        $deleteForm = $this->createDeleteForm($membershipStatus);
        $editForm = $this->createForm('AppBundle\Form\MembershipStatusType', $membershipStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('membershipstatus_edit', array('id' => $membershipStatus->getId()));
        }

        return $this->render('membershipstatus/edit.html.twig', array(
            'membershipStatus' => $membershipStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a membershipStatus entity.
     *
     * @Route("/{id}", name="membershipstatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MembershipStatus $membershipStatus)
    {
        $form = $this->createDeleteForm($membershipStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($membershipStatus);
            $em->flush($membershipStatus);
        }

        return $this->redirectToRoute('membershipstatus_index');
    }

    /**
     * Creates a form to delete a membershipStatus entity.
     *
     * @param MembershipStatus $membershipStatus The membershipStatus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MembershipStatus $membershipStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('membershipstatus_delete', array('id' => $membershipStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
