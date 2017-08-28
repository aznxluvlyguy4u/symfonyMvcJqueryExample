<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Entity\CompanyStatus;
use AppBundle\Entity\ContactPerson;
use AppBundle\Form\ContactPersonType;
use AppBundle\Form\EditCompanyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{

    const REQUEST_NOT_AUTHORIZED = 'You are not authorized.';
    const SERVER_ERROR = "An error occurred, please try again later";
    const CALL_SUCCESS = "Success";

    private $apikey;

    public function __construct()
    {
        $this->apikey = $this->getParameter('apikey');
    }

    /**
     * Resorts an item using it's doctrine sortable property
     * @param Request $request
     * @return Response
     * @Route("/contactpersons")
     * @Method("POST")
     */
    public function createContactPerson(Request $request)
    {

        if($this->checkAuthorization($request->get('apiKey'))) {

            try {

                $data = json_decode($request->getContent(), true);

                $contactPerson = new ContactPerson();
                $form = $this->createForm(ContactPersonType::class, $contactPerson, array('csrf_protection' => false));
                $form->submit($data);

                if ($form->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($contactPerson);
                    $em->flush();

                    return new JsonResponse(ApiController::CALL_SUCCESS, Response::HTTP_OK);
                }
            } catch (\Exception $e) {
                return new JsonResponse(ApiController::SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return new JsonResponse(ApiController::REQUEST_NOT_AUTHORIZED, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Resorts an item using it's doctrine sortable property
     * @param Request $request
     * @return Response
     * @Route("/companies")
     * @Method("POST")
     */
    public function createCompanyAction(Request $request)
    {

        if($this->checkAuthorization($request->get('apiKey'))) {

            try {
                $data = json_decode($request->getContent(), true);

                $em = $this->getDoctrine()->getManager();
                $companyStatusRepository = $em->getRepository(CompanyStatus::class);
                $companyStatus = $companyStatusRepository->findBy(['isDeleted' => false], ['position' => 'ASC'], 1);

                $data['status'] = $companyStatus[0]->getId();

                $company = new Company();
                $form = $this->createForm(EditCompanyType::class, $company, array('csrf_protection' => false));
                $form->submit($data);

                if ($form->isValid()) {

                    $em->persist($company);
                    $em->flush();

                    return new JsonResponse(ApiController::CALL_SUCCESS, Response::HTTP_OK);
                }
            } catch (\Exception $e) {

                return new JsonResponse(ApiController::SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return new JsonResponse(ApiController::REQUEST_NOT_AUTHORIZED, Response::HTTP_UNAUTHORIZED);
    }

    private function checkAuthorization($apiKey)
    {

        if($this->apikey === $apiKey)
            return true;

        return false;
    }

}
