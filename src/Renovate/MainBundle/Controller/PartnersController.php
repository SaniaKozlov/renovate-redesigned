<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Partner;
use Renovate\MainBundle\Entity\Document;

class PartnersController extends Controller
{
    public function indexAction()
    {
    	$timestamp = time();
    	$token = Document::getToken($timestamp);
    	return $this->render('RenovateMainBundle:Partners:index.html.twig', array(
    			'timestamp' => $timestamp,
    			'token' => $token
    	));
    }
    
    public function getPartnersNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Partner::getPartners($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getPartnersCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Partner::getPartnersCount($em))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function addPartnerNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$partner = Partner::addPartner($em, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $partner->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function removePartnerNgAction($partner_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Partner::removePartnerById($em, $partner_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function editPartnerNgAction($partner_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$partner = Partner::editPartnerById($em, $partner_id, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $partner->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function showPartnersBlockAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$parameters = array();
    	$parameters['partners'] = Partner::getPartners($em, array('onhomepage' => 1));
    	return $this->render('RenovateMainBundle:Partners:showPartnersBlock.html.twig', $parameters);
    }
}
