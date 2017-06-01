<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class GameController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function playAction(Request $request)
    {
        $idGamers = $request->query->get('gamer');
        $idSubjects = $request->query->get('subject');
        if ($idGamers === null || $idSubjects === null) {
            throw new Exception('Avant de jouer, vous devez d\'abord sélectionner des joueurs et des thèmes.');
        }
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

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function getRandomQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            $subjectId = $request->get('subjectId');
            $gamerId = $request->get('gamerId');

            $gamer = $em->getRepository('UserBundle:Gamer')->find($gamerId);
            $schoolClass = $gamer->getSchoolClass();

            if ($subjectId !== null && $gamerId !== null) {
                $subject = $em->getRepository('GameBundle:Subject')->find($subjectId);
                $questions = $em->getRepository('GameBundle:Question')->findBySubjectAndBySchoolClass($subject, $schoolClass);
                $idQuestionList = [];
                foreach($questions as $question) {
                    $idQuestionList[] = $question->getId();
                }
                $randomQuestion = $em->getRepository('GameBundle:Question')->getRandomQuestion($idQuestionList);
                if ($randomQuestion != null) {
                    return new JsonResponse($randomQuestion);
                } else {
                    return new JsonResponse('Aucune question n\'a pas été trouvée.');                    
                }
            }
            throw new Exception('L\'élément n\'a pas été trouvé : id null.');            
        }
        throw new Exception('Aucune requête n\'a été transmise.');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
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

            if ($answerId !== null && $gamerId !== null) {
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
                        'validity'  => 'Mauvaise réponse. Tu ne gagnes pas de point.'                        
                    ));                    
                }
            }
            throw new Exception('L\'élément n\'a pas été trouvé : id null.');
        }
        throw new Exception('Aucune requête n\'a été transmise.');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function changeStatsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {

            $winnerId = $request->get('winnerId');
            $gamerReturn = $em->getRepository('UserBundle:Gamer')->find($winnerId);
            $gameWonNb = $gamerReturn->getGameWonNb();
            $gameWonNb++;
            $gamerReturn->setGameWonNb($gameWonNb);
            $em->persist($gamerReturn);
            
            $allGamersId = array();
            $finalScores = array();
            $content = $request->getContent();
            if (!empty($content)) {
                $content = json_decode($content, true);
                $allGamersId = $content['allGamersId'];
                $finalScores = $content['finalScores'];
            }

            $gamers = [];
            foreach ($allGamersId as $id) {
                $gamers[] = $em->getRepository('UserBundle:Gamer')->find($id);
            }
            foreach ($gamers as $key=>$gamer) {
                $cumulScore = $gamer->getCumulScore();
                $cumulScore += $finalScores[$key];
                $gamer->setCumulScore($cumulScore);

                $bestScore = $gamer->getBestScore();
                if ($finalScores[$key] > $bestScore) {
                    $gamer->setBestScore($finalScores[$key]);
                }

                $gamePlayedNb = $gamer->getGamePlayedNb();
                $gamePlayedNb++;
                $gamer->setGamePlayedNb($gamePlayedNb);

                $level = $gamer->getLevel();
                if ($cumulScore > 1500 * ($level + 1)) {
                    $level++;
                    $gamer->setLevel($level);
                }
                $em->persist($gamer);
            }
            $em->flush();
            
            $gamers = [];
            foreach ($allGamersId as $id) {
                $gamers[] = $em->getRepository('UserBundle:Gamer')->findGamerInArray($id);
            }
            return new JsonResponse($gamers);
        }
        throw new Exception('Aucune requête n\'a été transmise.');
    }
}
