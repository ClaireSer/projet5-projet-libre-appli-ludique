<?php

namespace UserBundle\Controller;

use UserBundle\Entity\UserCount;
use UserBundle\Form\Type\UserCountType;
use UserBundle\Form\Type\UserCountEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class DefaultController extends Controller
{
    /**
     * display home page
     */
    public function indexAction() {
        return $this->render('UserBundle:Default:index.html.twig');
    }

    /**
     * display usercounts
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function manageUsersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $families = $em->getRepository('UserBundle:UserCount')->getUserByRole('ROLE_USER');
        $teachers = $em->getRepository('UserBundle:UserCount')->getUserByRole('ROLE_TEACHER');
        $admins = $em->getRepository('UserBundle:UserCount')->getUserByRole('ROLE_ADMIN');
        
        return $this->render('UserBundle:Default:user_manager.html.twig', array(
            'families'      => $families,
            'teachers'      => $teachers,
            'admins'        => $admins
        ));   
    }

    /**
     * add user
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addUserAction(Request $request)
    {
        $userCount = new UserCount();
        $form = $this->createForm(UserCountType::class, $userCount);
        $formRequest = $form->handleRequest($request);

        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($userCount, $userCount->getPassword());
            $userCount->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($userCount);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le compte a bien été créé.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('UserBundle:Default:form_user.html.twig', array(
            'form'          => $form->createView(),
            'title'         => 'Ajout d\'un utilisateur',
            'titleTab'      => 'Ajout'
        ));
    }

    /**
     * edit user
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editUserAction(Request $request, UserCount $userCount)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserCountEditType::class, $userCount);
        $formRequest = $form->handleRequest($request);

        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $em->persist($userCount);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le compte a bien été édité.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('UserBundle:Default:form_user.html.twig', array(
            'form'      => $form->createView(),
            'title'     => 'Édition d\'un utilisateur',
            'titleTab'  => 'Édition'
        ));
    }

    /**
     * delete user
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteUserAction(Request $request, UserCount $userCount)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($userCount);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Le compte a bien été supprimé.');
        return $this->redirectToRoute('homepage');
    }
}
