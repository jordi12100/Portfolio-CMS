<?php

namespace Application\HomePageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ApplicationHomePageBundle:Default:index.html.twig');
    }
}
