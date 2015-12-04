<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Page;

class PagesController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Pages:index.html.twig');
    }
    
    public function getPagesNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Page::getPages($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getPagesCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Page::getPagesCount($em))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function addPageNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	 
    	$page = Page::addPage($em, $parameters);
    	 
    	$response = new Response(json_encode(array("result" => $page->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    
    public function removePageNgAction($page_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Page::removePageById($em, $page_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function editPageNgAction($page_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	 
    	$page = Page::editPageById($em, $page_id, $parameters);
    	 
    	$response = new Response(json_encode(array("result" => $page->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
