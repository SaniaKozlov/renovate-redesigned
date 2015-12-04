<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Article;
use Renovate\MainBundle\Entity\Document;

class ArticlesController extends Controller
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
    	
    	return $this->render('RenovateMainBundle:Articles:index.html.twig', $parameters);
    }
    
    public function showArticleAction($article_name_translit)
    {
        $timestamp = time();
        $token = Document::getToken($timestamp);

    	$em = $this->getDoctrine()->getManager();
        /** @var Article $article */
    	$article = $em->getRepository("RenovateMainBundle:Article")->findOneByNameTranslit($article_name_translit);
    	
    	if ($article == NULL) return $this->redirect($this->generateUrl('renovate_main_homepage'));
    	
    	$parameters = array(
            'article' => $article,
            'timestamp' => $timestamp,
            'token' => $token
        );
    	
    	$parameters['page'] = $this->get('renovate.pages')->getPageForUrl($this->getRequest()->getUri());
    	
    	return $this->render('RenovateMainBundle:Articles:showArticle.html.twig', $parameters);
    }

    public function getSingleArticleAction(Request $request)
    {
        $params = $request->query->all();

        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository('RenovateMainBundle:Article')->find($params['article_id']);

        return new JsonResponse($article->getInArray());
    }
    
    public function getArticlesNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Article::getArticles($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    public function getArticlesCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Article::getArticlesCount($em))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function addArticleNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$transliterater = $this->get('renovate.transliterater');
    	$article = Article::addArticle($em, $transliterater, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $article->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function removeArticleNgAction($article_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Article::removeArticleById($em, $article_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    
    public function editArticleNgAction($article_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	
    	$transliterater = $this->get('renovate.transliterater');
    	$article = Article::editArticleById($em, $transliterater, $article_id, $parameters);
    	
    	$response = new Response(json_encode(array("result" => $article->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
