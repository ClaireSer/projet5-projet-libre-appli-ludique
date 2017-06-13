<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use GameBundle\Form\Type\QuestionType;
use GameBundle\Entity\Question;


class HomeFamilyController extends Controller
{
    /**
     * get questions from usercount
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function getUserQuestionsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $myValidQuestions = $em->getRepository('GameBundle:Question')->findMyQuestions($this->getUser(), true);
        $myNotValidQuestions = $em->getRepository('GameBundle:Question')->findMyQuestions($this->getUser(), false);
        
        return $this->render('GameBundle:Default:user_questions.html.twig', array(
            'title'                 => 'Vos questions',
            'validQuestions'        => $myValidQuestions,
            'notValidQuestions'     => $myNotValidQuestions
        ));
    }

    /**
     * display help to play
     *
     * @Security("has_role('ROLE_USER') or has_role('ROLE_TEACHER')")
     */
    public function helpAction(Request $request) {
        return $this->render('GameBundle:Default:help.html.twig');
    } 
    
}
