<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\PriceCategory;

class PriceCategoriesController extends Controller
{
    public function getPriceCategoriesAllNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => PriceCategory::getAllPriceCategories($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function addPriceCategoryNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	 
    	$priceCategory = PriceCategory::addPriceCategory($em, $parameters);
    	 
    	$response = new Response(json_encode(array("result" => $priceCategory->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function editPriceCategoryNgAction($priceCategory_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	 
    	$priceCategory = PriceCategory::editPriceCategoryById($em, $priceCategory_id, $parameters);
    	 
    	$response = new Response(json_encode(array("result" => $priceCategory->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function removePriceCategoryNgAction($priceCategory_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => PriceCategory::removePriceCategoryById($em, $priceCategory_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
