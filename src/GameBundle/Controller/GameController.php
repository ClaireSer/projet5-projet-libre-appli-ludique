<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class GameController extends Controller
{
    public function playAction(Request $request)
    {
        $idArray = $request->query->get('gamer');
        $em = $this->getDoctrine()->getManager();
        $gamers = [];
        foreach ($idArray as $id) {
            $gamers[] = $em->getRepository('UserBundle:Gamer')->find($id);
        }

        return $this->render('GameBundle:Game:play.html.twig', array(
            'title'     => 'À vous de jouer !',
            'titleTab'  => 'Let\'s play !',
            'gamers'      => $gamers
        ));
    }
}
