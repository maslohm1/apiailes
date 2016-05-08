<?php

namespace BP\AilesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BPAilesBundle:Default:index.html.twig', array('name' => $name));
    }
}
