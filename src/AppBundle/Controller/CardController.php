<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\CompanySector;
use AppBundle\Form\CardType;
use AppBundle\Form\CompanySectorType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Contract;
use AppBundle\Form\FormContractType;

/**
 * @Route("/card")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class CardController extends Controller
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $cards = $em->getRepository('AppBundle:Card')->findAll();

        return [
            'cards' => $cards,
        ];
    }


    /**
     * @Route("/create")
     * @Template
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $card = new Card();
        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $card->setCreatedBy($this->getUser());
            $em->persist($card);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_card_edit', ['card' => $card->getId()]);
            } else {
                return $this->redirectToRoute('app_card_index');
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
    public function editAction(Request $request, Card $card)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $card->setCreatedBy($this->getUser());
            $em->persist($card);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_card_edit', ['card' => $card->getId()]);
            } else {
                return $this->redirectToRoute('app_card_index');
            }
        }

        return [
            'form' => $form->createView(),
            'contract' => $card,
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

        return $this->redirectToRoute('app_card_index');
    }
}
