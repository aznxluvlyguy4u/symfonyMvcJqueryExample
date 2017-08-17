<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Entity\ContactPerson;
use AppBundle\Form\ContactPersonType;
use AppBundle\Form\EditCompanyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{

    const CALL_NOT_AUTHORIZED = 'You are not authorized.';
    const CALL_SUCCESS = "Success";
    /**
     * Resorts an item using it's doctrine sortable property
     * @Route("/contactpersons/create")
     * @Method("POST")
     */
    public function createContactPerson(Request $request)
    {

        if($request->get('apiKey') === $this->getParameter('apikey')) {

            try {

                $data = json_decode($request->getContent(), true);

                $contactPerson = new ContactPerson();
                $form = $this->createForm(ContactPersonType::class, $contactPerson, array('csrf_protection' => false));
                $form->submit($data);

                if ($form->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($contactPerson);
                    $em->flush();

                    return new JsonResponse(ApiController::CALL_SUCCESS, 200);
                }
            } catch (\Exception $e) {
                return new JsonResponse($e->getMessage(), 417);
            }
        }

        return new JsonResponse(ApiController::CALL_NOT_AUTHORIZED, 500);
    }

    /**
     * Resorts an item using it's doctrine sortable property
     * @Route("/companies/create")
     * @Method("POST")
     */
    public function createCompanyAction(Request $request)
    {

        if($request->get('apiKey') === $this->getParameter('apikey')) {

            try {
                $data = json_decode($request->getContent(), true);

                $em = $this->getDoctrine()->getManager();
                $status = $em->getRepository('AppBundle:CompanyStatus')->findBy(['isDeleted' => false], ['position' => 'ASC'], 1);

                $data['status'] = $status[0]->getId();

                $company = new Company();
                $form = $this->createForm(EditCompanyType::class, $company, array('csrf_protection' => false));
                $form->submit($data);

                if ($form->isValid()) {

                    $em->persist($company);
                    $em->flush();

                    return new JsonResponse(ApiController::CALL_SUCCESS, 200);
                }
            } catch (\Exception $e) {

                return new JsonResponse($e->getMessage(), 417);
            }
        }

        return new JsonResponse(ApiController::CALL_NOT_AUTHORIZED, 401);
    }

}
