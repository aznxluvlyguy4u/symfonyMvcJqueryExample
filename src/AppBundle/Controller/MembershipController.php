<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Membership;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CompanyStatus;
use AppBundle\Form\MembershipType;

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
            $company = $membership->getCompany()->setStatus(
                $em->getRepository(CompanyStatus::class)
                    ->findOneBy(['label' => 'Contract signed'])
            );
            $em->persist($membership);
            $em->persist($company);
            $em->flush();

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
        $redirect = $request->query->get('redirect') ? $request->query->get('redirect') : 'app_membership_index';
        $editForm = $this->createForm(MembershipType::class, $membership, ['redirect' => $redirect]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if ($editForm->get('save')->isClicked()) {
                return $this->redirectToRoute('app_membership_edit', ['membership' => $membership->getId(), 'redirect' => $redirect]);
            } else {
                return $this->redirectToRoute($editForm->get('redirect')->getData());
            }
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
     * @Method("GET")
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
