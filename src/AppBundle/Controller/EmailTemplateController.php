<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EmailTemplate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Emailtemplate controller.
 *
 * @Route("emailtemplate")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class EmailTemplateController extends Controller
{
    /**
     * Lists all emailTemplate entities.
     *
     * @Route("/")
     * @Method("GET")
     * @Template
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $emailTemplates = $em->getRepository('AppBundle:EmailTemplate')->findAll();

        return [
            'emailTemplates' => $emailTemplates,
        ];
    }

    /**
     * Creates a new emailTemplate entity.
     *
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function createAction(Request $request)
    {
        $emailTemplate = new Emailtemplate();
        $form = $this->createForm('AppBundle\Form\EmailTemplateType', $emailTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailTemplate->setCreatedBy($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($emailTemplate);
            $em->flush($emailTemplate);

            return $this->redirectToRoute('app_emailtemplate_edit', array('id' => $emailTemplate->getId()));
        }

        return [
            'emailTemplate' => $emailTemplate,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing emailTemplate entity.
     *
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, EmailTemplate $emailTemplate)
    {
        //$deleteForm = $this->createDeleteForm($emailTemplate);
        $editForm = $this->createForm('AppBundle\Form\EmailTemplateType', $emailTemplate);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_emailtemplate_edit', array('id' => $emailTemplate->getId()));
        }

        return [
            'emailTemplate' => $emailTemplate,
            'form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a emailTemplate entity.
     *
     * @Route("/{id}")
     * @Template
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

        return $this->redirectToRoute('app_emailtemplate_index');
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
            ->setAction($this->generateUrl('app_emailtemplate_delete', array('id' => $emailTemplate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
