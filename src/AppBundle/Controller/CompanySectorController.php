<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CompanySector;
use AppBundle\Form\CompanySectorType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Contract;
use AppBundle\Form\FormContractType;

/**
 * @Route("/configuration/sector")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class CompanySectorController extends Controller
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $sectors = $em->getRepository('AppBundle:CompanySector')->findAll();

        return [
            'sectors' => $sectors,
        ];
    }


    /**
     * @Route("/create")
     * @Template
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sector = new CompanySector();
        $form = $this->createForm(CompanySectorType::class, $sector);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sector->setCreatedBy($this->getUser());
            $em->persist($sector);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_companysector_edit', ['sector' => $sector->getId()]);
            } else {
                return $this->redirectToRoute('app_companysector_index');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/{sector}")
     * @Template
     */
    public function editAction(Request $request, CompanySector $sector)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CompanySectorType::class, $sector);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sector->setCreatedBy($this->getUser());
            $em->persist($sector);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_companysector_edit', ['sector' => $sector->getId()]);
            } else {
                return $this->redirectToRoute('app_companysector_index');
            }
        }

        return [
            'form' => $form->createView(),
            'contract' => $sector,
        ];
    }

    /**
     * @Route("/delete/{sector}")
     * @Template
     */
    public function deleteAction(Request $request, CompanySector $sector)
    {
        $em = $this->getDoctrine()->getManager();

        $sector->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_companysector_index');
    }
}
