<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\News;
use Renovate\MainBundle\Entity\Document;

class NewsController extends Controller
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
    	
    	return $this->render('RenovateMainBundle:News:index.html.twig', $parameters);
    }
    
    public function showNewsAction($news_name_translit)
    {
    	$em = $this->getDoctrine()->getManager();
    	$news = $em->getRepository("RenovateMainBundle:News")->findOneByNameTranslit($news_name_translit);
    	
    	if ($news == NULL) return $this->redirect($this->generateUrl('renovate_main_homepage'));
    	
    	$parameters = array("news" => $news);
    	
    	$parameters['page'] = $this->get('renovate.pages')->getPageForUrl($this->getRequest()->getUri());
    	
    	return $this->render('RenovateMainBundle:News:showNews.html.twig', $parameters);
    }
    
    public function getNewsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => News::getNews($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getNewsCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => News::getNewsCount($em))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function addNewsNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$transliterater = $this->get('renovate.transliterater');
    	$news = News::addNews($em, $transliterater, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $news->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function removeNewsNgAction($news_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => News::removeNewsById($em, $news_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function editNewsNgAction($news_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$transliterater = $this->get('renovate.transliterater');
    	$news = News::editNewsById($em, $transliterater, $news_id, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $news->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
