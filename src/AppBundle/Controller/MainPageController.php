<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Notice;
use Symfony\Component\HttpFoundation\Response;

class MainPageController extends Controller
{
    /**
     * @Route("/", name="main");
     * @Template("AppBundle::main.html.twig");
     */
    public function showLandingPageAction(){
        return ['notices' => $this->getDoctrine()->getRepository('AppBundle:Notice')->findAll()];
    }
}
