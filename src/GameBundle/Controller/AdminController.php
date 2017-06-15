<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use GameBundle\Form\Type\QuestionType;
use GameBundle\Form\Type\TopicType;
use GameBundle\Form\Type\ThemeType;
use GameBundle\Entity\Question;
use GameBundle\Entity\Topic;
use GameBundle\Entity\Subject;


class AdminController extends Controller
{
    /**
     * add question
     *
     * @Security("has_role('ROLE_TEACHER') or has_role('ROLE_USER')")
     */
    public function addQuestionAction(Request $request)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        
        $formRequest = $form->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                $question->setIsValid(false);
            } else {
                $question->setIsValid(true);                
            }
            $question->setUserCount($this->getUser());
            foreach($question->getAnswers() as $answer) {
                $answer->setQuestion($question);
            }
            $firstAnswer = $question->getAnswers()->first();
            $firstAnswer->setIsRight(true);

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
     * validate a question
     *
     * @Security("has_role('ROLE_USER') or has_role('ROLE_TEACHER')")
     */
    public function validateQuestionAction(RequestStack $request, Question $question)
    {
        $form = $this->createForm(QuestionType::class, $question);

        // $formRequest = $form->handleRequest($request);
        // if ($formRequest->isSubmitted() && $formRequest->isValid()) {
        //     $question->setIsValid(true);

        //     foreach($question->getAnswers() as $answer) {
        //         $answer->setQuestion($question);
        //     }
        //     $firstAnswer = $question->getAnswers()->first();
        //     $firstAnswer->setIsRight(true);
            
        //     $em = $this->getDoctrine()->getManager();
        //     $em->persist($question);
        //     $em->flush();
        //     $request->getSession()->getFlashBag()->add('success', 'La question a bien été validée.');
        //     return $this->redirectToRoute('moderate_question');
        // }

        // return $this->render('GameBundle:Default:form_question.html.twig', array(
        //     'form'  => $form->createView(),
        //     'title' => 'Validation de questions'
        // ));
        $saving = $this->get('save.question');
        $saving->save($question, $form, $request);
    }

    /**
     * display all questions and informations about question stats
     *
     * @Security("has_role('ROLE_TEACHER')")
     */
    public function moderateQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $notValidQuestions = $em->getRepository('GameBundle:Question')->getQuestionsByValidity(false);
        $validQuestions = $em->getRepository('GameBundle:Question')->getQuestionsByValidity(true);

        $infos = $this->get('stats.question');
        $arrayInfos = $infos->getInfo();
        
        return $this->render('GameBundle:Admin:moderate_question.html.twig', array(
            'notValidQuestions'     => $notValidQuestions,
            'validQuestions'        => $validQuestions,
            'subjects'              => $arrayInfos['subjects'],
            'nbSchoolLevels'        => $arrayInfos['nbSchoolClassBySubject'],
            'schoolLevels'          => $arrayInfos['schoolClassBySubject'],
            'nbQuestions'           => $arrayInfos['nbQuestions']
        ));   
    }
    
    /**
     * ajax call to remove an answer on the form
     *
     * @Security("has_role('ROLE_USER') or has_role('ROLE_TEACHER')")
     */
    public function removeAnswerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            $idAnswer = $request->get('id');
            if ($idAnswer !== null) {
                $answer = $em->getRepository('GameBundle:Answer')->find($idAnswer);
                $em->remove($answer);
                $em->flush();
                return new JsonResponse();
            }
            return $this->render('TwigBundle:Exception:error.html.twig', array(
                'status_text'      => 'L\'élément n\'a pas été trouvé.'
            ));
        }
        return $this->render('TwigBundle:Exception:error.html.twig', array(
            'status_text'      => 'Aucune requête n\'a été transmise.'
        ));        
    }

    /**
     * delete question
     *
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
     * add subject or add topic
     *
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

        if (($formThemeRequest->isSubmitted() && $formThemeRequest->isValid()) || ($formTopicRequest->isSubmitted() && $formTopicRequest->isValid())) {
            $subject = $topic->getSubject();
            $subject->addTopic($topic);

            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le nouvel élément a bien été enregistré.');
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
     * delete topic
     *
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
     * delete subject
     *
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
