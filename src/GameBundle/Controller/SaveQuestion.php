<?php

namespace GameBundle\Controller;

use GameBundle\Entity\Question;
use Symfony\Component\HttpFoundation\RequestStack;

class SaveQuestion
{
    private $request;

    public function __construct(RequestStack $request) {
        $this->request = $request;
    }
    public function save(Question $question, $form) {
        $formRequest = $form->handleRequest($this->request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $question->setIsValid(true);
            foreach($question->getAnswers() as $answer) {
                $answer->setQuestion($question);
            }
            $firstAnswer = $question->getAnswers()->first();
            $firstAnswer->setIsRight(true);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            $this->request->getSession()->getFlashBag()->add('success', 'La question a bien été validée.');
            return $this->redirectToRoute('moderate_question');
        }

        return $this->render('GameBundle:Default:form_question.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Validation de questions'
        ));
    }
}
