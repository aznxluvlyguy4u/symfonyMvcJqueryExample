<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MembershipStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Membershipstatus controller.
 *
 * @Route("/configuration/membershipstatus")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class MembershipStatusController extends Controller
{
    /**
     * Lists all membershipStatus entities.
     *
     * @Route("/")
     * @Method("GET")
     * @Template
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $membershipStatuses = $em->getRepository('AppBundle:MembershipStatus')->findBy(array(), array('position' => 'ASC'));

        return [
            'membershipStatuses' => $membershipStatuses,
        ];
    }

    /**
     * Creates a new membershipStatus entity.
     *
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function createAction(Request $request)
    {
        $membershipStatus = new Membershipstatus();
        $form = $this->createForm('AppBundle\Form\MembershipStatusType', $membershipStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membershipStatus);
            $em->flush($membershipStatus);

            return $this->redirectToRoute('app_membershipstatus_edit', array('id' => $membershipStatus->getId()));
        }

        return [
            'membershipStatus' => $membershipStatus,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing membershipStatus entity.
     *
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, MembershipStatus $membershipStatus)
    {
        //$deleteForm = $this->createDeleteForm($membershipStatus);
        $editForm = $this->createForm('AppBundle\Form\MembershipStatusType', $membershipStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_membershipstatus_edit', array('id' => $membershipStatus->getId()));
        }

        return [
            'membershipStatus' => $membershipStatus,
            'form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Resorts an item using it's doctrine sortable property
     * @Route("/sort/{id}/{position}")
     * @Template("AppBundle:MembershipStatus:index.html.twig")
     * @Method("GET")
     */
    public function sortAction(Request $request, $id, $position)
    {
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $membershipStatus = $em->getRepository('AppBundle:MembershipStatus')->find($id);
            $membershipStatus->setPosition($position);
            $em->persist($membershipStatus);
            $em->flush();
            $request = new Request();
            return $this->indexAction($request);
        }
    }

    /**
     * Deletes a membershipStatus entity.
     *
     * @Route("/{id}")
     * @Template
     */
    public function deleteAction(Request $request, MembershipStatus $membershipStatus)
    {
        $em = $this->getDoctrine()->getManager();

        $membershipStatus->setPosition(-1);
        $membershipStatus->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_membershipstatus_index');
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
            ->setAction($this->generateUrl('app_membershipstatus_delete', array('id' => $membershipStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
