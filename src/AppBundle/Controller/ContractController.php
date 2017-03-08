<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Contract;
use AppBundle\Form\FormContractType;

/**
 * @Route("/contract")
 */
class ContractController extends Controller
{
    /**
     * @Route("/")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contracts = $em->getRepository('AppBundle:Contract')->findAll();

        return [
            'contracts' => $contracts,
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

        $contract = new Contract();
        $form = $this->createForm(FormContractType::class, $contract);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contract->setCreatedBy($this->getUser());
            $contract->setEndDate(null);
            $em->persist($contract);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_contract_edit', ['contract' => $contract->getId()]);
            } else {
                return $this->redirectToRoute('app_contract_index');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/{contract}")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function editAction(Request $request, Contract $contract)
    {
        $em = $this->getDoctrine()->getManager();

        $contract = new Contract();
        $form = $this->createForm(FormContractType::class, $contract);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contract->setCreatedBy($this->getUser());
            $em->persist($contract);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_contract_edit', ['contract' => $contract->getId()]);
            } else {
                return $this->redirectToRoute('app_contract_index');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/delete/{contract}")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Request $request, Contract $contract)
    {
        $em = $this->getDoctrine()->getManager();

        $contract->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_contract_index');
    }
}
