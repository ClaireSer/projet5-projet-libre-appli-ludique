<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Gamer;
use UserBundle\Entity\UserCount;
use UserBundle\Form\UserCountSignupType;
use UserBundle\Form\UserCountSettingsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SessionController extends Controller
{
    public function signupAction(Request $request) {
        $userCount = new UserCount();
        $form = $this->createForm(UserCountSignupType::class, $userCount);
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($userCount, $userCount->getPassword());
            $userCount->setPassword($password);
            $userCount->setRoles(array('ROLE_USER'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($userCount);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Compte bien enregistré.');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('UserBundle:Default:form_user.html.twig', array(
            'form'      => $form->createView(),
            'title'     => 'Formulaire d\'inscription',
            'titleTab'  => 'Inscription'
        ));
    }

    public function loginAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $request->getSession()->getFlashBag()->add('notice', 'Vous êtes bien connecté(e).');            
            return $this->redirectToRoute('homepage');
        }
        $authenticationUtils = $this->get('security.authentication_utils');
        return $this->render('UserBundle:Session:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    public function settingsAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $userCount = $em->getRepository('UserBundle:UserCount')->find($id);
        if (null === $userCount) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $form = $this->createForm(UserCountSettingsType::class, $userCount);
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($userCount, $userCount->getPassword());
            $userCount->setPassword($password);

            $em->persist($userCount);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le mot de passe a bien été modifié.');
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('UserBundle:Default:form_user.html.twig', array(
            'form'      => $form->createView(),
            'titleTab'  => 'Paramètres',
            'title'     => 'Modification du mot de passe'
        ));
    }
}