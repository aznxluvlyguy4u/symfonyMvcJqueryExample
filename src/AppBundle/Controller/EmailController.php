<?php

namespace AppBundle\Controller;

use AppBundle\Form\SendEmailType;
use AppBundle\Entity\EmailTemplate;
use AppBundle\Entity\Company;
use AppBundle\Entity\Membership;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swift_Message;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("/email")
 */
class EmailController extends Controller
{
    /**
     * @Route("/template/company/{company}")
     * @Method({"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function getTemplateForCompanyAction(Request $request, Company $company) {
        if($request->isXmlHttpRequest()) {
            if ($company) {
                $template = $company->getStatus()->getEmailTemplate();
                $subject = null;
                $body = null;
                if ($template) {
                    $subject = $template->getSubject();
                    $body = $this->get('twig')->createTemplate($template->getBody())->render(array('company' => $company));
                }
                return new JsonResponse(array(
                    'subject' => $subject,
                    'body' => $body
                ), Response::HTTP_OK);
            }
            throw new NotFoundHttpException();
        }
    }
    
    /**
     * @Route("/template/membership/{membership}")
     * @Method({"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function getTemplateForMembershipAction(Request $request, Membership $membership) {
        if($request->isXmlHttpRequest()) {
            if ($membership) {
                $template = $membership->getStatus()->getEmailTemplate();
                $subject = null;
                $body = null;
                if ($template) {
                    $subject = $template->getSubject();
                    $body = $this->get('twig')->createTemplate($template->getBody())->render(array('company' => $membership->getCompany()));
                }
                return new JsonResponse(array(
                    'subject' => $subject,
                    'body' => $body
                ), Response::HTTP_OK);
            }
            throw new NotFoundHttpException();
        }
    }

    /**
     * @Route("/send")
     * @Method({"POST"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function sendEmailToCompanyAction(Request $request) {
        $form = $this->createForm(SendEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mailer = $this->container->get('mailer');
            $message = new Swift_Message();
            $message
                ->setTo($form->get('to')->getData())
                ->setSubject($form->get('subject')->getData())
                ->setFrom($this->getParameter('mailer_source_address'))
                ->setBody($form->get('body')->getData())
                ->setContentType('text/html');
            try {
                $mailer->send($message);
            } catch (Exception $e) {
                $this->addFlash('Error', $e);
            }
            return $this->redirect($request->headers->get('referer'));
        }
    }
}
