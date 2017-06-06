<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Membership;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CompanyStatus;
use AppBundle\Entity\MembershipStatus;
use AppBundle\Entity\MembershipStatusHistory;
use AppBundle\Entity\MembershipComment;
use AppBundle\Form\CreateCommentType;
use AppBundle\Form\MembershipType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membership controller.
 *
 * @Route("membership")
 */
class MembershipController extends Controller
{
    /**
     * Lists all membership entities.
     *
     * @Route("/")
     * @Method("GET")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $memberships = $em->getRepository('AppBundle:Membership')->findBy(array('isDeleted' => false));

        return [
            'memberships' => $memberships,
        ];
    }

    /**
     * Creates a new membership entity.
     *
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $membership = new Membership();
        $form = $this->createForm('AppBundle\Form\MembershipType', $membership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membership->setCreatedBy($this->getUser());
            $company = $membership->getCompany()->setStatus(
                $em->getRepository(CompanyStatus::class)
                    ->findOneBy(['label' => 'Contract signed'])
            );
            $em->persist($membership);
            $em->persist($company);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_membership_edit', ['membership' => $membership->getId()]);
            } else {
                return $this->redirectToRoute('app_membership_index');
            }
        }

        return [
            'membership' => $membership,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing membership entity.
     *
     * @Route("/edit/{membership}")
     * @Method({"GET", "POST"})
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function editAction(Request $request, Membership $membership)
    {
        $em = $this->getDoctrine()->getManager();

        $redirect = $request->query->get('redirect') ? $request->query->get('redirect') : 'app_membership_index';
        $editForm = $this->createForm(MembershipType::class, $membership, ['redirect' => $redirect]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // Calculate changeset on entity
            $uow = $em->getUnitOfWork();
            $uow->computeChangeSets();
            $changeset = $uow->getEntityChangeSet($membership);

            if(array_key_exists('status', $changeset)) {
                list($previousStatus, $currentStatus) = $changeset['status'];

                $membershipStatusHistory = new MembershipStatusHistory();
                $membershipStatusHistory->setMembership($membership);
                $membershipStatusHistory->setPreviousStatus($previousStatus);
                $membershipStatusHistory->setCurrentStatus($currentStatus);
                $membershipStatusHistory->setCreatedBy($this->getUser());

                $membershipComment = new MembershipComment();
                $membershipComment->setMembership($membership);
                $membershipComment->setText('Changed status from <b>' . $previousStatus->getLabel() .  '</b> to <b>' . $currentStatus->getLabel() . '</b>.');
                $membershipComment->setCreatedBy($this->getUser());

                $em->persist($membershipStatusHistory);
                $em->persist($membershipComment);
            }

            $em->persist($membership);
            $em->flush();

            if ($editForm->get('save')->isClicked()) {
                return $this->redirectToRoute('app_membership_edit', ['membership' => $membership->getId(), 'redirect' => $redirect]);
            } else {
                return $this->redirectToRoute($editForm->get('redirect')->getData());
            }
        }

        $membershipComment = new MembershipComment();
        $membershipCommentForm = $this->createForm(CreateCommentType::class, $membershipComment);

        $membershipCommentForm->handleRequest($request);

        if ($membershipCommentForm->isSubmitted() && $membershipCommentForm->isValid()) {
            $membershipComment->setMembership($membership);
            $membershipComment->setCreatedBy($this->getUser());
            $em->persist($membershipComment);
            $em->flush();

            if ($membershipCommentForm->get('save')->isClicked()) {
                return $this->redirectToRoute('app_membership_edit', ['membership' => $membership->getId()]);
            } else {
                return $this->redirectToRoute('app_membership_index');
            }
        }

        return [
            'companyCommentForm' => $membershipCommentForm->createView(),
            'membership' => $membership,
            'form' => $editForm->createView(),
        ];
    }

    /**
     * @Route("/update/{membership}/status/{status}", requirements={"membership": "\d+", "status": "\d+"})
     * @Method("PUT")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function updateStatusAction(Request $request, Membership $membership, MembershipStatus $status)
    {
        if($request->isXmlHttpRequest()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $previousStatus = $membership->getStatus();
                $currentStatus = $status;

                $membershipStatusHistory = new MembershipStatusHistory();
                $membershipStatusHistory->setMembership($membership);
                $membershipStatusHistory->setPreviousStatus($previousStatus);
                $membershipStatusHistory->setCurrentStatus($currentStatus);
                $membershipStatusHistory->setCreatedBy($this->getUser());

                $membershipComment = new MembershipComment();
                $membershipComment->setMembership($membership);
                $membershipComment->setText('Changed status from <b>' . $previousStatus->getLabel() .  '</b> to <b>' . $currentStatus->getLabel() . '</b>.');
                $membershipComment->setCreatedBy($this->getUser());

                $membership->setStatus($currentStatus);

                $em->persist($membershipStatusHistory);
                $em->persist($membershipComment);
                $em->persist($membership);
                $em->flush();

                return new JsonResponse(array(
                ), Response::HTTP_OK);
            } catch (Exception $e) {
                return new JsonResponse(array(
                    'error' => $e
                ), Response::CONFLICT);
            }
        }

        throw new BadRequestHttpException();
    }
    
    /**
     * Upload Files
     * @Route("/upload")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method("GET")
     */
    public function uploadDocument(Request $request)
    {
        $AWSS3 = $this->get('app.aws.storageservice');
        $client = $AWSS3->getS3Client();
        $root = $this->get('kernel')->getRootDir();
        $file = $root.'/../web/img/rose.png';
//        $bucket = $client->listObjects(['Bucket' => 'the-hague-tech']);
//        dump($bucket);die();

        $upload = $AWSS3->uploadFromFilePath($file, 'wtf.png', 'image/png');
        dump($upload);die();
    }
    

    /**
     * Deletes a membership entity.
     *
     * @Route("/delete/{membership}")
     * @Method("GET")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Request $request, Membership $membership)
    {
        $em = $this->getDoctrine()->getManager();

        $membership->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_membership_index');
    }
}
