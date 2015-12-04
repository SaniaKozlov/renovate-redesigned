<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Event;

class EventsController extends Controller
{
    public function getEventsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Event::getEvents($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }

    public function editEventNgAction($event_id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode(file_get_contents("php://input"));
        $parameters = (object) $data;

        $event = Event::editEventById($em, $event_id, $parameters);

        $response = new Response(json_encode(array("result" => ($event)?$event->getInArray():false)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function addEventNgAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode(file_get_contents("php://input"));
        $parameters = (object) $data;

        $event = Event::addEvent($em, $parameters);

        $response = new Response(json_encode(array("result" => ($event)?$event->getInArray():false)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function removeEventNgAction($event_id)
    {
        $em = $this->getDoctrine()->getManager();

        $response = new Response(json_encode(array("result" => Event::removeEventById($em, $event_id))));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
