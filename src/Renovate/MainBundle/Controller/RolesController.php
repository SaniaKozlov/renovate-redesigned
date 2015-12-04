<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Role;

class RolesController extends Controller
{
    public function getRolesNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Role::getRoles($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getClientRolesNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Role::getClientRoles($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function getSimpleRolesNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Role::getSimpleRoles($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getPrivilegesRolesNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Role::getPrivilegesRoles($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
