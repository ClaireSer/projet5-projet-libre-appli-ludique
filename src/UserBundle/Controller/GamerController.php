<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Gamer;
use UserBundle\Form\GamerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class GamerController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function managerAction(Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        $userCount = $this->getUser();
        $gamers = $em->getRepository('UserBundle:Gamer')->getGamersByUserCount($userCount);
        return $this->render('UserBundle:Gamer:admin_gamers.html.twig', array(
            'gamers'    => $gamers
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
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
            'form'          => $form->createView(),
            'title'         => 'Ajouter un joueur'
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Gamer $gamer)
    {
        $em = $this->getDoctrine()->getManager();
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

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Gamer $gamer)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($gamer);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Le joueur a bien été supprimée.');
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Security("has_role('ROLE_USER') or has_role('ROLE_TEACHER')")
     */
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

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function selectAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $gamers = $em->getRepository('UserBundle:Gamer')->getGamersByUserCount($this->getUser());
        $subjectsSelected = $em->getRepository('GameBundle:Subject')->findByCountingSchoolClass();
        $subjects = $em->getRepository('GameBundle:Subject')->findAll();

        $subjectIds = [];
        foreach ($subjects as $subject) {
            $subjectIds[] = $subject->getId();
        }

        $allSchoolClass = $em->getRepository('GameBundle:SchoolClass')->findAll();
        $schoolClassIds = [];
        foreach ($allSchoolClass as $schoolClass) {
            $schoolClassIds[] = $schoolClass->getId();
        }

        $nbSchoolClassBySubject = [];
        $schoolClassBySubject = [];
        $nbQuestions = [];
        foreach ($subjectIds as $key=>$id0) {
            $nbSchoolClassBySubject[] = $em->getRepository('GameBundle:SchoolClass')->countBySubject($id0);
            $schoolClassBySubject[] = $em->getRepository('GameBundle:SchoolClass')->getBySubject($id0);
            foreach ($schoolClassIds as $id1) {
                $nbQuestions[$key][] = $em->getRepository('GameBundle:Question')->count($id0, $id1);
            }
        }

        return $this->render('UserBundle:Gamer:select_gamer.html.twig', array(
            'gamers'            => $gamers,
            'subjects'          => $subjects,
            'subjectsSelected'  => $subjectsSelected,
            'nbSchoolLevels'    => $nbSchoolClassBySubject,
            'schoolLevels'      => $schoolClassBySubject,
            'nbQuestions'       => $nbQuestions,
            'title1'            => 'Choisissez vos joueurs',
            'title2'            => 'Choisissez quatre thèmes',
            'titleTab'          => 'Le jeu'
        ));
    }
}