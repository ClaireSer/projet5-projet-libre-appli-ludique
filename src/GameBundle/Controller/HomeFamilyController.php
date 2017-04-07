<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GameBundle\Form\QuestionType;
use GameBundle\Entity\Question;


class HomeFamilyController extends Controller
{
    public function selectAction(Request $request)
    {
        return $this->render('GameBundle:Default:select_gamer.html.twig');
    }
    
    public function checkScoresAction(Request $request)
    {
        return $this->render('GameBundle:Default:check_scores.html.twig');        
    }

    public function manageGamersAction(Request $request)
    {
        return $this->render('GameBundle:Default:admin_gamers.html.twig');
    }

    public function suggestQuestionAction(Request $request)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $question->setIsValid(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Question bien enregistrée.');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('GameBundle:Default:form_question.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Proposez vos questions'
        ));
    }
}
