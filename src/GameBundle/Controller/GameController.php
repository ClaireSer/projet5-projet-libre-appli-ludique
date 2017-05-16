<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class GameController extends Controller
{
    public function playAction(Request $request)
    {
        $idGamers = $request->query->get('gamer');
        $idSubjects = $request->query->get('subject');
        $em = $this->getDoctrine()->getManager();
        
        // get gamers selected
        $gamers = [];
        foreach ($idGamers as $id) {
            $gamers[] = $em->getRepository('UserBundle:Gamer')->find($id);
        }

        // get subjects selected
        $subjects = [];
        foreach ($idSubjects as $id) {
            $subjects[] = $em->getRepository('GameBundle:Subject')->find($id);
        }

        return $this->render('GameBundle:Game:play.html.twig', array(
            'title'         => 'À vous de jouer !',
            'titleTab'      => 'Let\'s play !',
            'gamers'        => $gamers,
            'subjects'      => $subjects
        ));
    }

    public function getRandomQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()) {
            $subjectId = $request->get('subjectId');
            $gamerId = $request->get('gamerId');

            $gamer = $em->getRepository('UserBundle:Gamer')->find($gamerId);
            $schoolClass = $gamer->getSchoolClass();

            if ($subjectId != null) {
                $subject = $em->getRepository('GameBundle:Subject')->find($subjectId);
                $questions = $em->getRepository('GameBundle:Question')->getQuestionBySubject($subject);
                $idQuestionList = [];
                foreach($questions as $question) {
                    $idQuestionList[] = $question->getId();
                }
                $randomQuestion = $em->getRepository('GameBundle:Question')->getRandomQuestionBySubject($subject, $schoolClass, $idQuestionList);
                if ($randomQuestion != null) {
                    return new JsonResponse($randomQuestion);
                } else {
                    return new JsonResponse('Aucune question n\'a pas été trouvée.');                    
                }
            }
            return new Response('L\'élément n\'a pas été trouvé : id null.');            
        }
        return new Response('Erreur');
    }

    public function validAnswerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()) {
            $answerId = $request->get('answerId');
            $gamerId = $request->get('gamerId');
            $dice = $request->get('dice');
            $score = $request->get('score');
            $bonusDifficulty = $request->get('bonus');
            $scoreQuestion = $dice * $bonusDifficulty;
            $score += $scoreQuestion;

            if ($answerId != null) {
                $answerReturn = $em->getRepository('GameBundle:Answer')->find($answerId);
                $gamerReturn = $em->getRepository('UserBundle:Gamer')->find($gamerId);

                if ($answerReturn->getIsRight()) {
                    $gamerReturn->setRightAnswerNb(1 + $gamerReturn->getRightAnswerNb());
                    $em->persist($gamerReturn);
                    $em->flush();
                    return new JsonResponse(array(
                        'validity'      => 'Bonne réponse !',
                        'infoScore'     => $scoreQuestion,
                        'rightAnswerNb' => $gamerReturn->getRightAnswerNb(),
                        'score'         => $score
                    ));
                } else {
                    return new JsonResponse(array(
                        'validity'  => 'Mauvaise réponse. Tu ne gagnes pas de points.'                        
                    ));                    
                }
            }
            return new Response('L\'élément n\'a pas été trouvé : id null.');
        }
        return new Response('Une erreur est survenue. Re-tentez la demande.');
    }

    public function changeStatsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()) {
            $finalScore = $request->get('finalScore');
            $bestScore = $request->get('bestScore');
            $gamerId = $request->get('gamerId');
            $gameWonNb = $request->get('gameWonNb');
            $gameWonNb++;
            $cumulScore = $request->get('cumulScore');
            $cumulScore += $finalScore;
            $level = $request->get('level');
            $level++;
            // $index = $request->get('index');

            $gamerReturn = $em->getRepository('UserBundle:Gamer')->find($gamerId);
            $gamerReturn->setGameWonNb($gameWonNb);
            $gamerReturn->setCumulScore($cumulScore);
            if ($finalScore > $bestScore) {
                $gamerReturn->setBestScore($bestScore);
            }
            // if ($cumulScore > 300 * $index) {
            //     $gamerReturn->setLevel($level);                    
            //     $index++;
            // }
            $em->persist($gamerReturn);
            $em->flush();

            return new JsonResponse(array(
                'bestScore'     => $gamerReturn->getBestScore(),
                'gameWonNb'     => $gamerReturn->getGameWonNb(),
                'cumulScore'    => $gamerReturn->getCumulScore(),
                'level'         => $gamerReturn->getLevel()
            ));
        }
        return new Response('Erreur');
    }

    
}