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
        return $this->render('GameBundle:Admin:moderate_question.html.twig');   
    }
}
