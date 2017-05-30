<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use GameBundle\Form\QuestionType;
use GameBundle\Form\TopicType;
use GameBundle\Form\ThemeType;
use GameBundle\Entity\Question;
use GameBundle\Entity\Topic;
use GameBundle\Entity\Subject;


class AdminController extends Controller
{
    /**
     * @Security("has_role('ROLE_TEACHER')")
     */
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
            'title' => 'Ajoutez vos questions'
        ));
    }

    /**
     * @Security("has_role('ROLE_USER') or has_role('ROLE_TEACHER')")
     */
    public function moderateQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $notValidQuestions = $em->getRepository('GameBundle:Question')->getQuestionsByValidity(false);
        $validQuestions = $em->getRepository('GameBundle:Question')->getQuestionsByValidity(true);

        $subjects = $em->getRepository('GameBundle:Subject')->findAll();
        $subjectIds = [];
        foreach ($subjects as $subject) {
            $subjectIds[] = $subject->getId();
        }
        
        $allSchoolClass = $em->getRepository('GameBundle:SchoolClass')->findAll();
        $schoolClassIds = [];
        foreach ($allSchoolClass as $schoolClass) {
            $schoolClassIds[] = $schoolClass->getId();
        }

        $nbSchoolClassBySubject = [];
        $schoolClassBySubject = [];
        $nbQuestions = [];
        foreach ($subjectIds as $key=>$id0) {
            $nbSchoolClassBySubject[] = $em->getRepository('GameBundle:SchoolClass')->countBySubject($id0);
            $schoolClassBySubject[] = $em->getRepository('GameBundle:SchoolClass')->getBySubject($id0);
            foreach ($schoolClassIds as $id1) {
                $nbQuestions[$key][] = $em->getRepository('GameBundle:Question')->count($id0, $id1);
            }
        }

        return $this->render('GameBundle:Admin:moderate_question.html.twig', array(
            'notValidQuestions'     => $notValidQuestions,
            'validQuestions'        => $validQuestions,
            'subjects'              => $subjects,
            'nbSchoolLevels'        => $nbSchoolClassBySubject,
            'schoolLevels'          => $schoolClassBySubject,
            'nbQuestions'           => $nbQuestions
        ));   
    }
    
    /**
     * @Security("has_role('ROLE_TEACHER')")
     */
    public function validateQuestionAction(Request $request, Question $question)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(QuestionType::class, $question);
        $formRequest = $form->handleRequest($request);
        
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $question->setIsValid(true);

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
            $request->getSession()->getFlashBag()->add('success', 'La question a bien été validée.');
            return $this->redirectToRoute('moderate_question');
        }

        return $this->render('GameBundle:Default:form_question.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Validation de questions'
        ));
    }

    /**
     * @Security("has_role('ROLE_USER') or has_role('ROLE_TEACHER')")
     */
    public function removeAnswerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            $idAnswer = $request->get('id');
            if ($idAnswer != null) {
                $answer = $em->getRepository('GameBundle:Answer')->find($idAnswer);
                $em->remove($answer);
                $em->flush();
                return new JsonResponse();
            }
            throw new Exception('L\'élément n\'a pas été trouvé.');
        }
        throw new Exception('Aucune requête n\'a été transmise.');
    }

    /**
     * @Security("has_role('ROLE_USER') or has_role('ROLE_TEACHER')")
     */
    public function deleteQuestionAction(Request $request, Question $question)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($question);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'La question a bien été supprimée.');
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Security("has_role('ROLE_TEACHER')")
     */
    public function optionsQuestionAction(Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        $topic = new Topic();
        $formTheme = $this->createForm(ThemeType::class, $topic);
        $formTopic = $this->createForm(TopicType::class, $topic);
        $formThemeRequest = $formTheme->handleRequest($request);
        $formTopicRequest = $formTopic->handleRequest($request);

        if ($formThemeRequest->isSubmitted() && $formThemeRequest->isValid()) {
            $subject = $topic->getSubject();
            $subject->addTopic($topic);

            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le nouveau thème a bien été enregistré.');
            return $this->redirectToRoute('options_question');
        }

        if ($formTopicRequest->isSubmitted() && $formTopicRequest->isValid()) {
            $subject = $topic->getSubject();
            $subject->addTopic($topic);

            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'La nouvelle catégorie a bien été enregistrée.');
            return $this->redirectToRoute('options_question');
        }

        $subjects = $em->getRepository('GameBundle:Subject')->findBy(array(), array('id' => 'ASC'));

        return $this->render('GameBundle:Admin:options_question.html.twig', array(
            'formTheme'     => $formTheme->createView(),
            'formTopic'     => $formTopic->createView(),
            'subjects'      => $subjects
        ));
    }

    /**
     * @Security("has_role('ROLE_TEACHER')")
     */
    public function deleteTopicAction(Request $request, Topic $topic)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($topic);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'La sous-matière a bien été supprimée.');
        return $this->redirectToRoute('options_question');
    }

    /**
     * @Security("has_role('ROLE_TEACHER')")
     */
        public function deleteSubjectAction(Request $request, Subject $subject)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subject);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'La matière a bien été supprimée.');
        return $this->redirectToRoute('options_question');
    }
}
