<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EmailTemplate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Emailtemplate controller.
 *
 * @Route("emailtemplate")
 */
class EmailTemplateController extends Controller
{
    /**
     * Lists all emailTemplate entities.
     *
     * @Route("/", name="emailtemplate_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $emailTemplates = $em->getRepository('AppBundle:EmailTemplate')->findAll();

        return $this->render('emailtemplate/index.html.twig', array(
            'emailTemplates' => $emailTemplates,
        ));
    }

    /**
     * Creates a new emailTemplate entity.
     *
     * @Route("/new", name="emailtemplate_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $emailTemplate = new Emailtemplate();
        $form = $this->createForm('AppBundle\Form\EmailTemplateType', $emailTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailTemplate->setCreatedBy($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($emailTemplate);
            $em->flush($emailTemplate);

            return $this->redirectToRoute('emailtemplate_show', array('id' => $emailTemplate->getId()));
        }

        return $this->render('emailtemplate/new.html.twig', array(
            'emailTemplate' => $emailTemplate,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a emailTemplate entity.
     *
     * @Route("/{id}", name="emailtemplate_show")
     * @Method("GET")
     */
    public function showAction(EmailTemplate $emailTemplate)
    {
        $deleteForm = $this->createDeleteForm($emailTemplate);

        return $this->render('emailtemplate/show.html.twig', array(
            'emailTemplate' => $emailTemplate,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing emailTemplate entity.
     *
     * @Route("/{id}/edit", name="emailtemplate_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, EmailTemplate $emailTemplate)
    {
        $deleteForm = $this->createDeleteForm($emailTemplate);
        $editForm = $this->createForm('AppBundle\Form\EmailTemplateType', $emailTemplate);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('emailtemplate_edit', array('id' => $emailTemplate->getId()));
        }

        return $this->render('emailtemplate/edit.html.twig', array(
            'emailTemplate' => $emailTemplate,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a emailTemplate entity.
     *
     * @Route("/{id}", name="emailtemplate_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, EmailTemplate $emailTemplate)
    {
        $form = $this->createDeleteForm($emailTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($emailTemplate);
            $em->flush($emailTemplate);
        }

        return $this->redirectToRoute('emailtemplate_index');
    }

    /**
     * Creates a form to delete a emailTemplate entity.
     *
     * @param EmailTemplate $emailTemplate The emailTemplate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(EmailTemplate $emailTemplate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('emailtemplate_delete', array('id' => $emailTemplate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
