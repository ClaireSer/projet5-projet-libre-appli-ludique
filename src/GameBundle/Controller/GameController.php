<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class GameController extends Controller
{
    /**
     * get selected gamers et subjects and display board game
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function playAction(Request $request)
    {
        $idGamers = $request->query->get('gamer');
        $idSubjects = $request->query->get('subject');
        if ($idGamers === null || $idSubjects === null) {
            return $this->render('TwigBundle:Exception:error.html.twig', array(
                'status_text'      => 'Avant de jouer, vous devez d\'abord sélectionner des joueurs et des thèmes.'
            ));
        }
        $em = $this->getDoctrine()->getManager();
        
        // get gamers selected and put in session
        $gamers = [];
        foreach ($idGamers as $id) {
            $gamers[$id] = $em->getRepository('UserBundle:Gamer')->find($id);
        }
        $request->getSession()->set("gamers", $gamers);

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
     * get a random question by gamer et subject given
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function getRandomQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            $subjectId = $request->get('subjectId');
            $gamerId = $request->get('gamerId');
            $gamer = $em->getRepository('UserBundle:Gamer')->find($gamerId);

            if ($subjectId !== null && $gamerId !== null) {
                $subject = $em->getRepository('GameBundle:Subject')->find($subjectId);
                $questions = $em->getRepository('GameBundle:Question')->findBySubjectAndBySchoolClass($subject, $gamer->getSchoolClass());
                $idQuestionList = [];
                foreach($questions as $question) {
                    $idQuestionList[] = $question->getId();
                }
                $randomQuestion = $em->getRepository('GameBundle:Question')->getRandomQuestion($idQuestionList);
                if ($randomQuestion == null) {
                    return new JsonResponse('Aucune question n\'a pas été trouvée.');                    
                }
                return new JsonResponse($randomQuestion);
            }
            return $this->render('TwigBundle:Exception:error.html.twig', array('status_text' => 'L\'élément n\'a pas été trouvé : id null.'));
        }
        return $this->render('TwigBundle:Exception:error.html.twig', array('status_text' => 'Aucune requête n\'a été transmise.'));
    }

    /**
     * check if answer is valid and update gamers main scores
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function validAnswerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            $answerId = $request->get('answerId');
            $gamerId = $request->get('gamerId');
            $dice = $request->get('dice');
            $score = $request->get('score');
            $bonusDifficulty = $request->get('bonus');
            $scoreQuestion = $dice * $bonusDifficulty;
            $score += $scoreQuestion;

            if ($answerId !== null && $gamerId !== null) {
                $answerReturn = $em->getRepository('GameBundle:Answer')->find($answerId);
                $gamers = $request->getSession()->get("gamers");
                // $gamerReturn = $gamers[$gamerId];
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
            return $this->render('TwigBundle:Exception:error.html.twig', array('status_text' => 'L\'élément n\'a pas été trouvé : id null.'));
        }
        return $this->render('TwigBundle:Exception:error.html.twig', array('status_text' => 'Aucune requête n\'a été transmise.'));
    }

    /**
     * once the game is done, change stats
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function changeStatsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {

            $winnerId = $request->get('winnerId');
            $gamerReturn = $em->getRepository('UserBundle:Gamer')->find($winnerId);
            $gamerReturn->setGameWonNb($gamerReturn->getGameWonNb() + 1);
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
                $updating = $this->container->get('update.gamer');
                $updating->update($gamer, $finalScores[$key]);
                $em->persist($gamer);
            }
            $em->flush();
            
            $gamers = [];
            foreach ($allGamersId as $id) {
                $gamers[] = $em->getRepository('UserBundle:Gamer')->findGamerInArray($id);
            }
            return new JsonResponse($gamers);
        }
        return $this->render('TwigBundle:Exception:error.html.twig', array('status_text' => 'Aucune requête n\'a été transmise.'));        
    }
}
