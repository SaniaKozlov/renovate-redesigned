<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Tariff;

class TariffsController extends Controller
{
    public function indexAction()
    {
    	$parameters = array();
    	$parameters['page'] = $this->get('renovate.pages')->getPageForUrl($this->getRequest()->getUri());
    	 
    	return $this->render('RenovateMainBundle:Tariffs:index.html.twig',$parameters);
    }
    
    public function showPanelAction()
    {
    	return $this->render('RenovateMainBundle:Tariffs:showPanel.html.twig');
    }
    
    public function getTariffsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Tariff::getTariffs($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getTariffsCountNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Tariff::getTariffsCount($em, $request->query->all()))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function removeTariffNgAction($tariff_id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Tariff::removeTariffById($em, $tariff_id))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function addTariffPublicNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	 
    	$tariff = Tariff::addTariffPublic($em, $parameters);
    	 
    	$response = new Response(json_encode(array("result" => $tariff->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function editTariffPublicNgAction($tariff_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    
    	$tariff = Tariff::editTariffPublicById($em, $tariff_id, $parameters);
    
    	$response = new Response(json_encode(array("result" => $tariff->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function addTariffPrivateNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    
    	$tariff = Tariff::addTariffPrivate($em, $this->getUser(), $parameters);
    
    	$response = new Response(json_encode(array("result" => $tariff->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function editTariffPrivateNgAction($tariff_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    
    	$tariff = Tariff::editTariffPrivateById($em, $tariff_id, $parameters);
    
    	$response = new Response(json_encode(array("result" => $tariff->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function activateTariffPrivateNgAction($tariff_id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Tariff::activateTariffPrivateById($em, $tariff_id))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
