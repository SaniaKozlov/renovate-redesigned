<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Cost;

class CostsController extends Controller
{
    public function addCostNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    
    	$cost = Cost::addCost($em, $parameters);
    
    	$response = new Response(json_encode(array("result" => $cost->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function removeCostNgAction($cost_id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Cost::removeCostById($em, $cost_id))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function editCostNgAction($cost_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    
    	$cost = Cost::editCostById($em, $cost_id, $parameters);
    
    	$response = new Response(json_encode(array("result" => $cost->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function filterCostNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Cost::filterCosts($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
