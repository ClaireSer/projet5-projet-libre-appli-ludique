<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Gamer;
use UserBundle\Form\Type\GamerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class GamerController extends Controller
{
    /**
     * display gamers of current usercount
     *
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
     * add gamer
     *
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
            $gamer->setGamePlayedNb(0);
            $gamer->setRightAnswerNb(0);
            $gamer->setLevel(0);
            $gamer->setUserCount($this->getUser());

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
     * edit gamer
     *
     * @Security("has_role('ROLE_USER') and user.getId() == gamer.getUserCount().getId()")
     */

    public function editAction(Request $request, Gamer $gamer)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(GamerType::class, $gamer);
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $em->persist($gamer);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Les informations du joueur ont bien été modifiées.');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('UserBundle:Gamer:form_gamer.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Éditer un joueur'
        ));
    }

    /**
     * delete gamer
     *
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
     * display scores of all gamers
     *
     * @Security("has_role('ROLE_USER') or has_role('ROLE_TEACHER')")
     */
    public function listScoresAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $schoolClass = $em->getRepository('GameBundle:SchoolClass')->findAll();

        $schoolClassIds = [];
        $schoolClassTitles = [];
        foreach ($schoolClass as $schoolClass) {
            $schoolClassIds[] = $schoolClass->getId();
            $schoolClassTitles[] = $schoolClass->getSchoolClass();
        }
        $myGamers = $em->getRepository('UserBundle:Gamer')->getGamersByUserCount($this->getUser());
        $gamers = $em->getRepository('UserBundle:Gamer')->getGamers();

        $gamersBySchoolClass = [];
        foreach ($schoolClassIds as $id) {
            $gamersBySchoolClass[] = $em->getRepository('UserBundle:Gamer')->getGamersBySchoolClass($id);
        }

        $otherGamers = $em->getRepository('UserBundle:Gamer')->getOtherGamers();
        
        return $this->render('UserBundle:Gamer:list_scores.html.twig', array(
            'myGamers'              => $myGamers,
            'gamers'                => $gamers,
            'gamersBySchoolClass'   => $gamersBySchoolClass,
            'schoolClassTitles'     => $schoolClassTitles,
            'otherGamers'           => $otherGamers
        ));
    }

    /**
     * display gamers and subjects, and informations about questions stats
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function selectAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $gamers = $em->getRepository('UserBundle:Gamer')->getGamersByUserCount($this->getUser());
        $subjectsSelected = $em->getRepository('GameBundle:Subject')->findByCountingSchoolClass();

        $infos = $this->get('stats.question');
        $arrayInfos = $infos->getInfo();

        return $this->render('UserBundle:Gamer:select_gamer.html.twig', array(
            'gamers'            => $gamers,
            'subjectsSelected'  => $subjectsSelected,
            'subjects'          => $arrayInfos['subjects'],
            'nbSchoolLevels'    => $arrayInfos['nbSchoolClassBySubject'],
            'schoolLevels'      => $arrayInfos['schoolClassBySubject'],
            'nbQuestions'       => $arrayInfos['nbQuestions']
        ));
    }
}
