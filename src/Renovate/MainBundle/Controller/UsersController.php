<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\User;

class UsersController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Users:index.html.twig');
    }
    
    public function showUserAction($user_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = $em->getRepository("RenovateMainBundle:User")->find($user_id);
    	 
    	$parameters = array("user" => $user);
    	 
    	return $this->render('RenovateMainBundle:Users:showUser.html.twig', $parameters);
    }
    
    public function showMeAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$parameters = array("user" => $this->getUser());
    
    	return $this->render('RenovateMainBundle:Users:showUser.html.twig', $parameters);
    }
    
    public function getUsersNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => User::getUsers($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getWorkersNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => User::getWorkers($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getClientsNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => User::getClients($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getWorkforceNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => User::getWorkforce($em, true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getUsersCountNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => User::getUsersCount($em, $request->query->all()))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function addUserNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$ef = $this->get('security.encoder_factory');
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$user = User::addUser($em, $ef, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $user->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function removeUserNgAction($user_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => User::removeUserById($em, $user_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function editUserNgAction($user_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$ef = $this->get('security.encoder_factory');
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$user = User::editUserById($em, $ef, $user_id, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $user->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function checkUserTariffNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => $this->getUser()->checkUserTariff($em))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function checkUserUsernameNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => User::checkUserUsername($em, $request->query->all()))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function checkUserEmailNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => User::checkUserEmail($em, $request->query->all()))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
