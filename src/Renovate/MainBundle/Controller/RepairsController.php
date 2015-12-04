<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Repair;

class RepairsController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Repairs:index.html.twig');
    }
    
    public function getRepairsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Repair::getRepairs($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getRepairsCountNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Repair::getRepairsCount($em, $request->query->all()))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function setRepairsPaidNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$response = new Response(json_encode(array("result" => Repair::setRepairsPaid($em, $parameters))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function addRepairNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$repair = Repair::addRepair($em, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $repair->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function removeRepairNgAction($repair_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Repair::removeRepairById($em, $repair_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function editRepairNgAction($repair_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$repair = Repair::editRepairById($em, $repair_id, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $repair->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
