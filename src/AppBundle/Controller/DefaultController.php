<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('poll_default_index');
        }
        // replace this example code with whatever you need
        return $this->render('AppBundle:Default:index.html.twig');
    }
}
