<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\ServiceCategory;

class ServiceCategoriesController extends Controller
{
    public function getServiceCategoriesAllNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => ServiceCategory::getAllServiceCategories($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
