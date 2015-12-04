<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Task;

class TasksController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Tasks:index.html.twig');
    }
    
    public function getTasksNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Task::getTasks($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getTasksCountNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Task::getTasksCount($em, $request->query->all()))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function addTaskNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$task = Task::addTask($em, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $task->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function removeTaskNgAction($task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Task::removeTaskById($em, $task_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function editTaskNgAction($task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$task = Task::editTaskById($em, $task_id, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $task->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function finishTaskNgAction($task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$task = Task::finishTaskById($em, $task_id);
    	 
    	$response = new Response(json_encode(array("result" => $task->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function approveTaskNgAction($task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$task = Task::approveTaskById($em, $task_id);
    
    	$response = new Response(json_encode(array("result" => $task->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function declineTaskNgAction($task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$task = Task::declineTaskById($em, $task_id);
    
    	$response = new Response(json_encode(array("result" => $task->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
