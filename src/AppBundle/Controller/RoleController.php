<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\CompanySector;
use AppBundle\Entity\Role;
use AppBundle\Form\CardType;
use AppBundle\Form\CompanySectorType;
use AppBundle\Form\RoleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Contract;
use AppBundle\Form\FormContractType;

/**
 * @Route("/role")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class RoleController extends Controller
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $roles = $em->getRepository('AppBundle:Role')->findAll();

        return [
            'roles' => $roles,
        ];
    }


    /**
     * @Route("/create")
     * @Template
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role->setCreatedBy($this->getUser());
            $em->persist($role);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_role_edit', ['role' => $role->getId()]);
            } else {
                return $this->redirectToRoute('app_role_index');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/{card}")
     * @Template
     */
    public function editAction(Request $request, Role $role)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CardType::class, $role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role->setCreatedBy($this->getUser());
            $em->persist($role);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_role_edit', ['role' => $role->getId()]);
            } else {
                return $this->redirectToRoute('app_role_index');
            }
        }

        return [
            'form' => $form->createView(),
            'role' => $role,
        ];
    }

    /**
     * @Route("/delete/{card}")
     * @Template
     */
    public function deleteAction(Request $request, Card $card)
    {
        $em = $this->getDoctrine()->getManager();

        $card->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_role_index');
    }
}
