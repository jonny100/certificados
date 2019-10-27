<?php

namespace App\Application\ToolsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApplicationToolsBundle:Default:index.html.twig', array('name' => $name));
    }
}
