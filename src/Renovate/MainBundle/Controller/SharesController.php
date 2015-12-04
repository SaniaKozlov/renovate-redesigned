<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Share;
use Renovate\MainBundle\Entity\Document;

class SharesController extends Controller
{
    public function indexAction()
    {
    	$timestamp = time();
    	$token = Document::getToken($timestamp);
    	
    	$parameters = array(
    			'timestamp' => $timestamp,
    			'token' => $token
    	);
    	
    	$parameters['page'] = $this->get('renovate.pages')->getPageForUrl($this->getRequest()->getUri());
    	
    	return $this->render('RenovateMainBundle:Shares:index.html.twig', $parameters);
    }
    
    public function showShareAction($share_name_translit)
    {
    	$em = $this->getDoctrine()->getManager();
    	$share = $em->getRepository("RenovateMainBundle:Share")->findOneByNameTranslit($share_name_translit);
    	
    	if ($share == NULL) return $this->redirect($this->generateUrl('renovate_main_homepage'));
    	
    	$parameters = array("share" => $share);
    	
    	$parameters['page'] = $this->get('renovate.pages')->getPageForUrl($this->getRequest()->getUri());
    	 
    	return $this->render('RenovateMainBundle:Shares:showShare.html.twig', $parameters);
    }
    
    public function getSharesNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Share::getShares($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getSharesCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Share::getSharesCount($em))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function addShareNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$transliterater = $this->get('renovate.transliterater');
    	$share = Share::addShare($em, $transliterater, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $share->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function removeShareNgAction($share_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Share::removeShareById($em, $share_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function editShareNgAction($share_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$transliterater = $this->get('renovate.transliterater');
    	$share = Share::editShareById($em, $transliterater, $share_id, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $share->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
