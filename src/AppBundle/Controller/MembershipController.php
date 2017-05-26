<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Membership;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Membership controller.
 *
 * @Route("membership")
 */
class MembershipController extends Controller
{
    /**
     * Lists all membership entities.
     *
     * @Route("/")
     * @Method("GET")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $memberships = $em->getRepository('AppBundle:Membership')->findAll();

        return [
            'memberships' => $memberships,
        ];
    }

    /**
     * Creates a new membership entity.
     *
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $membership = new Membership();
        $form = $this->createForm('AppBundle\Form\MembershipType', $membership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membership->setCreatedBy($this->getUser());
            $em->persist($membership);
            $em->flush($membership);

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_membership_edit', ['membership' => $membership->getId()]);
            } else {
                return $this->redirectToRoute('app_membership_index');
            }
        }

        return [
            'membership' => $membership,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing membership entity.
     *
     * @Route("/edit/{membership}")
     * @Method({"GET", "POST"})
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function editAction(Request $request, Membership $membership)
    {
        $editForm = $this->createForm('AppBundle\Form\MembershipType', $membership);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('membership_edit', array('id' => $membership->getId()));
        }

        return [
            'membership' => $membership,
            'form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a membership entity.
     *
     * @Route("/delete/{membership}")
     * @Method("DELETE")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Request $request, Membership $membership)
    {
        $em = $this->getDoctrine()->getManager();

        $membership->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_membership_index');
    }
}
