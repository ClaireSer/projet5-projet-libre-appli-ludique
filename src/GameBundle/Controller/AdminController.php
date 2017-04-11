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
            $topic = $question->getTopic();
            $topic->addQuestion($question);

            $em = $this->getDoctrine()->getManager();
            var_dump($question->getTopic()->getSubject());
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

    public function comboboxAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();
        if($request->isXmlHttpRequest())
        {
            $id = $request->get('id');
            if ($id != null)
            {
                $topics = $em->getRepository('GameBundle:Topic')->getTopicsFromSubject($id);
                return new JsonResponse($topics);
            }
        }
        return new Response('Erreur');
    }

    public function manageUsersAction(Request $request)
    {
        return $this->render('GameBundle:Admin:user_manager.html.twig');   
    }

    public function moderateQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $notValidQuestions = $em->getRepository('GameBundle:Question')->getQuestionsNotValid();
        return $this->render('GameBundle:Admin:moderate_question.html.twig', array(
            'notValidQuestions'     => $notValidQuestions
        ));   
    }
    
    public function validQuestionAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $notValidQuestion = $em->getRepository('GameBundle:Question')->find($id);

        $form = $this->createForm(QuestionType::class, $notValidQuestion);
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $notValidQuestion->setIsValid(true);
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
}
