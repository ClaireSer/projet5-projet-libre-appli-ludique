<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Gamer;
use UserBundle\Form\GamerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class GamerController extends Controller
{
    public function managerAction(Request $request) 
    {
        return $this->render('UserBundle:Gamer:admin_gamers.html.twig');
    }

    public function addAction(Request $request)
    {
        $gamer = new Gamer();
        $form = $this->createForm(GamerType::class, $gamer);
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $gamer->setBestScore(0);
            $gamer->setGameWonNb(0);
            $gamer->setRightAnswerNb(0);
            $gamer->setLevel(0);
            $gamer->setUserCount($this->getUser());

        }

        return $this->render('UserBundle:Gamer:form_gamer.html.twig', array(
            'form'  => $form->createView()
        ));
    }
}
