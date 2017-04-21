<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class GameController extends Controller
{
    public function playAction(Request $request)
    {
        return $this->render('GameBundle:Game:play.html.twig', array(
            'title' => 'À vous de jouer !',
            'titleTab' => 'Let\'s play !'
        ));
    }
}
