<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Notice;
use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class MainPageController extends Controller
{
    /**
     * @Route("/", name="main");
     * @Template("AppBundle::main.html.twig");
     */
    public function showLandingPageAction(){
        return ['notices' => $this->getDoctrine()->getRepository('AppBundle:Notice')->findAll()];
    }
    
    /**
     * @Route("/admin", name="admin");
     * @Template("AppBundle:Admin:admin.html.twig");
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function showCategoryAction(){
          return ['ok'];
        
    }
    
    /**
     * @Route ("/new", name="newCategory")
     * @Template("AppBundle:Admin:newCategory.html.twig")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(){

        return ['category' => $this->getDoctrine()->getRepository('AppBundle:Category')->findAll()];
    }
    
    
    /**
     * @Route("/addCategory", name="added")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addCategoryAction(Request $request){
        
        $category = new Category();
        $category->setName($request->request->get('name'));
                      
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();
        
        return $this->redirectToRoute('admin');

    }
    
}
