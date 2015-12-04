<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Estimation;

class EstimationsController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Estimations:index.html.twig');
    }
    
    public function getEstimationsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Estimation::getEstimations($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getEstimationNgAction($estimation_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$estimation = $em->getRepository("RenovateMainBundle:Estimation")->find($estimation_id);
    	 
    	if ($estimation != NULL) {
    		$response = new Response(json_encode(array("result" => $estimation->getInArray())));
    	}else{
    		$response = new Response(json_encode(array("result" => false)));
    	}
    	
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function showEstimationAction($estimation_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$estimation = $em->getRepository("RenovateMainBundle:Estimation")->find($estimation_id);
    
    	if ($estimation == NULL) return $this->redirect($this->generateUrl('renovate_main_homepage'));
    	
    	return $this->render('RenovateMainBundle:Estimations:showEstimation.html.twig', array('estimation'=>$estimation));
    }
    
    public function csvEstimationAction($estimation_id, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$estimation = $em->getRepository("RenovateMainBundle:Estimation")->find($estimation_id);
    
    	if ($estimation == NULL) return $this->redirect($this->generateUrl('renovate_main_homepage'));
    	 
    	$response = $this->render('RenovateMainBundle:Estimations:csvEstimation.csv.twig', array('estimation'=>$estimation));
    	$response->headers->set('Content-Disposition', 'attachment; filename=Кошторис_N'.$estimation_id.'_експорт_'.date("d-m-Y_H-i-s").'.csv');
    	$response->setCharset('Windows-1251');
    	
    	if (strpos($request->headers->get('User-Agent'), 'Windows')) 
    		$response->setContent("sep=	\n".iconv('utf-8', 'windows-1251', $response->getContent()));
    	
    	return $response;
    }
    
    public function getEstimationsCountNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Estimation::getEstimationsCount($em, $request->query->all()))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function saveEstimationNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    
    	$estimation = Estimation::saveEstimation($em, $parameters);
    
    	$response = new Response(json_encode(array("result" => $estimation->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function removeEstimationNgAction($estimation_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Estimation::removeEstimationById($em, $estimation_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function copyEstimationNgAction($estimation_id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Estimation::copyEstimationById($em, $estimation_id))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
