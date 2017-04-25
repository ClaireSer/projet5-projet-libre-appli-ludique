<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Gamer;
use UserBundle\Entity\UserCount;
use UserBundle\Form\UserCountEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class DefaultController extends Controller
{
    public function indexAction() {
        return $this->render('UserBundle:Default:index.html.twig');
    }

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

    public function editUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $userCount = $em->getRepository('UserBundle:UserCount')->find($id);

        $form = $this->createForm(UserCountEditType::class, $userCount);
        $formRequest = $form->handleRequest($request);

        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $em->persist($userCount);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Compte bien édité.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('UserBundle:Default:form_user.html.twig', array(
            'form'      => $form->createView(),
            'title'     => 'Edition d\'un utilisateur',
            'titleTab'  => 'Edition'
        ));
    }

    public function deleteUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $userCount = $em->getRepository('UserBundle:UserCount')->find($id);

        $em->remove($userCount);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Le compte a bien été supprimé.');
        return $this->redirectToRoute('homepage');
    }
}
