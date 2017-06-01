<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/email")
 */
class EmailController extends Controller
{
    /**
     * @Route("/send")
     * @Method({"POST"})
     *
     */
    public function sendToCompanyWithTemplateAction(Request $request) {
//        dump($template);die();

        /* @var Swift_Mailer $mailer */
//        $mailer = $this->container->get('mailer');
//        $message = new \Swift_Message();
//        $message
//            ->setSubject('test')
//            ->setFrom($this->getParameter('mailer_source_address'))
//            ->setTo('yubinchen18@gmail.com')
//            ->setBody(
//                'asdf'
////                $this->renderView("AppBundle:Profile:index.html.twig")
//            );
//
//        $mailer->send($message);

        dump($request->request->all());
        die('yolo');
    }
}
