<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Profile;
use AppBundle\Form\CreateProfileType;
use AppBundle\Form\EditProfileType;

/**
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * @Route("/")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $profiles = $em->getRepository('AppBundle:Profile')->findAll();

        return [
            'profiles' => $profiles,
        ];
    }


    /**
     * @Route("/create")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $profile = new Profile();
        $form = $this->createForm(CreateProfileType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profile->setCreatedBy($this->getUser());
            $em->persist($profile);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_profile_edit', ['profile' => $profile->getId()]);
            } else {
                return $this->redirectToRoute('app_profile_index');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/{profile}")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function editAction(Request $request, Profile $profile)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(EditProfileType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($profile);
            $em->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('app_profile_edit', ['profile' => $profile->getId()]);
            } else {
                return $this->redirectToRoute('app_profile_index');
            }
        }

        return [
            'form' => $form->createView(),
            'profile' => $profile,
        ];
    }

    /**
     * @Route("/delete/{profile}")
     * @Template
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Request $request, Profile $profile)
    {
        $em = $this->getDoctrine()->getManager();

        $profile->setIsDeleted(true);

        $em->flush();

        return $this->redirectToRoute('app_profile_index');
    }
}
