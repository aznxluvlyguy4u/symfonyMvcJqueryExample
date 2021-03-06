<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Membership;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CompanyStatus;
use AppBundle\Entity\MembershipStatus;
use AppBundle\Entity\MembershipStatusHistory;
use AppBundle\Entity\MembershipComment;
use AppBundle\Form\CreateCommentType;
use AppBundle\Form\MembershipDocumentType;
use AppBundle\Form\MembershipType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membership controller.
 *
 * @Route("membership")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class MembershipController extends Controller
{
    /**
     * Lists all membership entities.
     *
     * @Route("/")
     * @Method("GET")
     * @Template
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
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $membership = new Membership();
        $form = $this->createForm('AppBundle\Form\MembershipType', $membership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membership->setCreatedBy($this->getUser());

            $em->persist($membership);
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
     */
    public function editAction(Request $request, Membership $membership)
    {
        $em = $this->getDoctrine()->getManager();
        $redirect = $request->query->get('redirect') ? $request->query->get('redirect') : 'app_membership_index';
        
        //Edit form block
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

        // Comments form block
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

        // Documents form block
        $membershipDocumentForm = $this->createForm(MembershipDocumentType::class, $membership, [
            'redirect' => $redirect,
            'action' => $this->generateUrl('app_membership_handledocument', ['membership' => $membership->getId()])
        ]);

        return [
            'companyCommentForm' => $membershipCommentForm->createView(),
            'membership' => $membership,
            'form' => $editForm->createView(),
            'membershipDocumentForm' => $membershipDocumentForm->createView()
        ];
    }

    /**
     * @Route("/{membership}/document")
     * @Method({"POST"})
     */
    public function handleDocumentAction(Request $request, Membership $membership)
    {
        $em = $this->getDoctrine()->getManager();
        $membershipDocumentForm = $this->createForm(MembershipDocumentType::class, $membership);
        $membershipDocumentForm->handleRequest($request);

        if ($membershipDocumentForm->isSubmitted() && $membershipDocumentForm->isValid()) {
            $em->persist($membership);
            $em->flush();

            if ($membershipDocumentForm->get('save')->isClicked()) {
                return $this->redirectToRoute('app_membership_edit', ['membership' => $membership->getId()]);
            } else {
                return $this->redirectToRoute('app_membership_index');
            }
        }
    }

    /**
     * @Route("/update/{membership}/status/{status}", requirements={"membership": "\d+", "status": "\d+"})
     * @Method("PUT")
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
     * Clears comments history.
     * @param Request $request
     * @param Membership $membership
     * @return RedirectResponse
     * @Route("/delete/{membership}/comments")
     * @Method("GET")
     * @Template
     */
    public function ClearCommentsAction(Request $request, Membership $membership)
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $membership->getComments();

        foreach($comments as $comment) {
            $em->remove($comment);
        }

        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Deletes a membership entity.
     * @param Request $request
     * @param Membership $membership
     * @return RedirectResponse
     * @Route("/delete/{membership}")
     * @Method("GET")
     * @Template
     */
    public function deleteAction(Request $request, Membership $membership)
    {
        $em = $this->getDoctrine()->getManager();

        $membership->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_membership_index');
    }

    
    public function downloadDocumentAction()
    {
        
    }
}
