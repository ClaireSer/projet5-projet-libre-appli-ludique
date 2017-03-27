<?php

namespace GameBundle\Controller;

use GameBundle\Entity\Gamer;
use GameBundle\Entity\UserCount;
use GameBundle\Form\UserCountType;
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
            return $this->redirectToRoute('game_homepage');
        }
        return $this->render('GameBundle:Session:signup.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function loginAction(Request $request) {
        return $this->render('GameBundle:Session:login.html.twig', array(
            'form' => $form->createView()
        ));
    }
}