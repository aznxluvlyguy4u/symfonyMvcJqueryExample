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
     * @Route("/company/{company}")
     * @Method({"GET", "POST"})
     *
     */
    public function sendToCompanyWithTemplateAction(Request $request, Company $company) {
        if($request->isXmlHttpRequest()) {

        }
    }
}
