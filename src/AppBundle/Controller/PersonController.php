<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Person;
use AppBundle\Form\CreatePersonType;
use AppBundle\Form\EditPersonType;

/**
 * @Route("/person")
 */
class PersonController extends Controller
{
    /**
     * @Route("/")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $people = $em->getRepository('AppBundle:Person')->findAll();

        return [
            'people' => $people,
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

        $person = new Person();
        $form = $this->createForm(CreatePersonType::class, $person);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $person->setCreatedBy($this->getUser());
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('app_person_edit', ['person' => $person->getId()]);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/{person}")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function editAction(Request $request, Person $person)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(EditPersonType::class, $person);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('app_person_edit', ['person' => $person->getId()]);
        }

        return [
            'form' => $form->createView(),
            'person' => $person,
        ];
    }

    /**
     * @Route("/delete/{person}")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Request $request, Person $person)
    {
        $em = $this->getDoctrine()->getManager();

        $person->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_person_index');
    }
}
