<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



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

        // // get a random question by subject
        // $randomQuestions = [];
        // foreach($subjects as $subject) {
        //     // get list of id questions by subject
        //     $questions = $em->getRepository('GameBundle:Question')->getQuestionBySubject($subject);
        //     $idQuestionList = [];
        //     foreach($questions as $question) {
        //         $idQuestionList[] = $question->getId();
        //     }
        //     $randomQuestions[] = $em->getRepository('GameBundle:Question')->getRandomQuestionBySubject($subject, $idQuestionList);
        // }

        return $this->render('GameBundle:Game:play.html.twig', array(
            'title'     => 'À vous de jouer !',
            'titleTab'  => 'Let\'s play !',
            'gamers'    => $gamers,
            'subjects'    => $subjects,
            // 'randomQuestions'  => $randomQuestions
        ));
    }

    public function getRandomQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()) {
            $subjectId = $request->get('id');
            if ($subjectId != null) {
                $subject = $em->getRepository('GameBundle:Subject')->find($subjectId);
                $questions = $em->getRepository('GameBundle:Question')->getQuestionBySubject($subject);
                $idQuestionList = [];
                foreach($questions as $question) {
                    $idQuestionList[] = $question->getId();
                }
                $randomQuestion = $em->getRepository('GameBundle:Question')->getRandomQuestionBySubject($subject, $idQuestionList);

                return new JsonResponse($randomQuestion);
            }
        }
        return new Response('Erreur');
    }
}