<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Payment;
use Renovate\MainBundle\Entity\Document;

class PaymentsController extends Controller
{
	public function indexAction()
	{
		$timestamp = time();
		$token = Document::getToken($timestamp);
		 
		$parameters = array(
				'timestamp' => $timestamp,
				'token' => $token
		);
		 
		return $this->render('RenovateMainBundle:Payments:index.html.twig', $parameters);
	}
	
	public function importAction(Request $request)
	{
		if ($request->getMethod() == 'POST') {

			$uploadedFile = $request->files->get('Filedata');
			
			$document = new Document();
			
			$timestamp = $request->request->get('timestamp');
			$token = $request->request->get('token');
			
			$document->setFile($uploadedFile, $timestamp, $token);
			
			if ($document->upload())
			{
				$em = $this->getDoctrine()->getManager();
				return new Response(json_encode(Payment::import($em, $document)));
			}
			
		}
		
		return new Response(json_encode(false));
	}
	
    public function getUserPaymentsNgAction($user_id, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Payment::getUserPayments($em, $user_id, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getUserPaymentsCountNgAction($user_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Payment::getUserPaymentsCount($em, $user_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function getMyPaymentsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Payment::getUserPayments($em, $this->getUser()->getId(), $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getMyPaymentsCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Payment::getUserPaymentsCount($em, $this->getUser()->getId()))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
