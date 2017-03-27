<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Gamer;
use UserBundle\Entity\UserCount;
use UserBundle\Form\UserCountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SessionController extends Controller
{
    public function signupAction(Request $request) {
        $userCount = new UserCount();
        $form = $this->createForm(UserCountType::class, $userCount);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userCount);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Compte bien enregistré.');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('UserBundle:Session:signup.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function loginAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('homepage');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('UserBundle:Session:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));

        // return $this->render('UserBundle:Session:login.html.twig', array(
        //     'form' => $form->createView()
        // ));
    }
}