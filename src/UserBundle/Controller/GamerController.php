<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Gamer;
use UserBundle\Form\GamerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GamerController extends Controller
{
    public function managerAction(Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        $userCount = $this->getUser();
        $gamers = $em->getRepository('UserBundle:Gamer')->getGamersByUserCount($userCount);
        return $this->render('UserBundle:Gamer:admin_gamers.html.twig', array(
            'gamers'    => $gamers
        ));
    }

    public function addAction(Request $request)
    {
        $gamer = new Gamer();
        $form = $this->createForm(GamerType::class, $gamer);
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $gamer->setCumulScore(0);
            $gamer->setBestScore(0);
            $gamer->setGameWonNb(0);
            $gamer->setRightAnswerNb(0);
            $gamer->setLevel(0);
            $gamer->setUserCount($this->getUser());
            $schoolClass = $gamer->getSchoolClass();
            $schoolClass->addGamer($gamer);

            $em = $this->getDoctrine()->getManager();
            $em->persist($gamer);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le joueur a bien été créé.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('UserBundle:Gamer:form_gamer.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Ajouter un joueur'
        ));
    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $gamer = $em->getRepository('UserBundle:Gamer')->find($id);
        $form = $this->createForm(GamerType::class, $gamer);
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            
            $schoolClass = $gamer->getSchoolClass();
            $schoolClass->addGamer($gamer);

            $em->persist($gamer);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Les informations du joueur ont bien été modifiées.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('UserBundle:Gamer:form_gamer.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Editer un joueur'
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $gamer = $em->getRepository('UserBundle:Gamer')->find($id);

        $em->remove($gamer);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Le joueur a bien été supprimée.');
        return $this->redirectToRoute('homepage');
    }

    public function listScoresAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $myGamers = $em->getRepository('UserBundle:Gamer')->getGamersByUserCount($this->getUser());
        $gamers = $em->getRepository('UserBundle:Gamer')->getGamers();
        $cpGamers = $em->getRepository('UserBundle:Gamer')->getGamersBySchoolClass('CP');
        $ce1Gamers = $em->getRepository('UserBundle:Gamer')->getGamersBySchoolClass('CE1');
        $ce2Gamers = $em->getRepository('UserBundle:Gamer')->getGamersBySchoolClass('CE2');
        $cm1Gamers = $em->getRepository('UserBundle:Gamer')->getGamersBySchoolClass('CM1');
        $cm2Gamers = $em->getRepository('UserBundle:Gamer')->getGamersBySchoolClass('CM2');
        $otherGamers = $em->getRepository('UserBundle:Gamer')->getOtherGamers();
        
        return $this->render('UserBundle:Gamer:list_scores.html.twig', array(
            'title'         => 'Scores des joueurs selon les niveaux',
            'titleTab'      => 'Scores',
            'myGamers'      => $myGamers,
            'gamers'        => $gamers,
            'cpGamers'      => $cpGamers,
            'ce1Gamers'     => $ce1Gamers,
            'ce2Gamers'     => $ce2Gamers,
            'cm1Gamers'     => $cm1Gamers,
            'cm2Gamers'     => $cm2Gamers,
            'otherGamers'   => $otherGamers
        ));
    }

    public function selectAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $gamers = $em->getRepository('UserBundle:Gamer')->getGamersByUserCount($this->getUser());
        $subjects = $em->getRepository('GameBundle:Subject')->findAll();
        $schoolLevels = $em->getRepository('GameBundle:SchoolClass')->findAll();

        return $this->render('UserBundle:Gamer:select_gamer.html.twig', array(
            'gamers'        => $gamers,
            'subjects'      => $subjects,
            'schoolLevels'  => $schoolLevels,
            'title1'        => 'Choisissez vos joueurs',
            'title2'        => 'Choisissez quatre thèmes',
            'titleTab'      => 'Le jeu'
        ));
    }
}