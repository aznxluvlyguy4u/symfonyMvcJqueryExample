<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\CompanyStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 *
 *
 * @Route("comment")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class CommentController extends Controller
{

    /**
     * Deletes a comment entity.
     *
     * @Template
     * @Route("/comment/{id}")
     */
    public function deleteAction(Request $request, Comment $comment)
    {

        $em = $this->getDoctrine()->getManager();

        $em->remove($comment);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
