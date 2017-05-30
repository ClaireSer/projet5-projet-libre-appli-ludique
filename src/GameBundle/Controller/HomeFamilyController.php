<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use GameBundle\Form\QuestionType;
use GameBundle\Entity\Question;


class HomeFamilyController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function suggestQuestionAction(Request $request)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $question->setIsValid(false);
            $question->setUserCount($this->getUser());
            foreach($question->getAnswers() as $answer) {
                $answer->setQuestion($question);
            }
            $firstAnswer = $question->getAnswers()->first();
            $firstAnswer->setIsRight(true);
            
            $topic = $question->getTopic();
            $topic->addQuestion($question);

            $schoolClass = $question->getSchoolClass();
            $schoolClass->addQuestion($question);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'La question a bien été enregistrée.');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('GameBundle:Default:form_question.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Proposez vos questions'
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function getUserQuestionsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $myValidQuestions = $em->getRepository('GameBundle:Question')->findMyQuestions($this->getUser(), true);
        $myNotValidQuestions = $em->getRepository('GameBundle:Question')->findMyQuestions($this->getUser(), false);
        
        return $this->render('GameBundle:Default:user_questions.html.twig', array(
            'title'         => 'Vos questions',
            'validQuestions'    => $myValidQuestions,
            'notValidQuestions'    => $myNotValidQuestions
        ));
    }

    /**
     * @Security("has_role('ROLE_USER') or has_role('ROLE_TEACHER')")
     */
    public function helpAction(Request $request) {
        return $this->render('GameBundle:Default:help.html.twig');
    } 
    
}
