<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GameBundle\Form\QuestionType;
use GameBundle\Entity\Question;
use Symfony\Component\HttpFoundation\JsonResponse;


class AdminController extends Controller
{
    public function addQuestionAction(Request $request)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $question->setIsValid(true);
            $question->setUserCount($this->getUser());
            foreach($question->getAnswers() as $answer) {
                $answer->setQuestion($question);
            }
            $firstAnswer = $question->getAnswers()->first();
            $firstAnswer->setIsRight(true);
            
            $topic = $question->getTopic();
            $topic->addQuestion($question);

            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Question bien enregistrée.');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('GameBundle:Default:form_question.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Ajoutez vos questions'
        ));
    }

    // public function comboboxAction(Request $request) 
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     if($request->isXmlHttpRequest()) {
    //         $id = $request->get('id');
    //         if ($id != null) {
    //             $topics = $em->getRepository('GameBundle:Topic')->getTopicsFromSubject($id);
    //             return new JsonResponse($topics);
    //         }
    //     }
    //     return new Response('Erreur');
    // }

    public function moderateQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $notValidQuestions = $em->getRepository('GameBundle:Question')->getQuestionsByValidity(false);
        $validQuestions = $em->getRepository('GameBundle:Question')->getQuestionsByValidity(true);
        return $this->render('GameBundle:Admin:moderate_question.html.twig', array(
            'notValidQuestions'     => $notValidQuestions,
            'validQuestions'     => $validQuestions
        ));   
    }
    
    public function validateQuestionAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $notValidQuestion = $em->getRepository('GameBundle:Question')->getQuestionById($id);
        $form = $this->createForm(QuestionType::class, $notValidQuestion);
        $formRequest = $form->handleRequest($request);
        
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $notValidQuestion->setIsValid(true);

            foreach($notValidQuestion->getAnswers() as $answer) {
                $answer->setQuestion($notValidQuestion);
            }
            $firstAnswer = $notValidQuestion->getAnswers()->first();
            $firstAnswer->setIsRight(true);
            
            $topic = $notValidQuestion->getTopic();
            $topic->addQuestion($notValidQuestion);

            $em = $this->getDoctrine()->getManager();
            $em->persist($notValidQuestion);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Question bien modifiée.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('GameBundle:Default:form_question.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Validation de questions'
        ));
    }

    public function removeAnswerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest()) {
            $idAnswer = $request->get('id');
            if ($idAnswer != null) {
                $answer = $em->getRepository('GameBundle:Answer')->find($idAnswer);
                $em->remove($answer);
                $em->flush();
                return new JsonResponse();
            }
        }
        return new Response('Erreur');
    }

    public function deleteQuestionAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('GameBundle:Question')->getQuestionById($id);

        $em->remove($question);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'La question a bien été supprimée.');
        return $this->redirectToRoute('homepage');
    }
}
