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
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class ContractController extends Controller
{
    /**
     * @Route("/")
     * @Template
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
     */
    public function editAction(Request $request, Contract $contract)
    {
        $em = $this->getDoctrine()->getManager();

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
            'contract' => $contract,
        ];
    }

    /**
     * @Route("/delete/{contract}")
     * @Template
     */
    public function deleteAction(Request $request, Contract $contract)
    {
        $em = $this->getDoctrine()->getManager();

        $contract->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_contract_index');
    }
}
