<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\CostCategory;

class CostCategoriesController extends Controller
{
    public function getCostCategoriesNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => CostCategory::getCostCategories($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function addCostCategoryNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    
    	$costCategory = CostCategory::addCostCategory($em, $parameters);
    
    	$response = new Response(json_encode(array("result" => $costCategory->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function removeCostCategoryNgAction($costCategory_id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => CostCategory::removeCostCategoryById($em, $costCategory_id))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function editCostCategoryNgAction($costCategory_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    
    	$costCategory = CostCategory::editCostCategoryById($em, $costCategory_id, $parameters);
    
    	$response = new Response(json_encode(array("result" => $costCategory->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
