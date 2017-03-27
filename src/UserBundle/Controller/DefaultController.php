<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Gamer;
use UserBundle\Entity\UserCount;
use UserBundle\Form\UserCountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class DefaultController extends Controller
{
    public function indexAction() {
        return $this->render('UserBundle:Default:index.html.twig');
        
    }
    
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $userCount = $em->getRepository('UserBundle:UserCount')->find($id);

        if (null === $userCount) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listGamers = $em
        ->getRepository('UserBundle:Gamer')
        ->findBy(array('userCount' => $userCount))
        ;

        return $this->render('UserBundle:Default:index.html.twig', array(
            'userCount'  => $userCount,
            'listGamers' => $listGamers
        ));
    }

    public function addAction(Request $request) {
        $userCount = new UserCount();
        $form = $this->createForm(UserCountType::class, $userCount);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userCount);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Compte bien enregistré.');
            return $this->redirectToRoute('gamer_view', array('id' => $userCount->getId()));
        }

        return $this->render('UserBundle:Default:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
}