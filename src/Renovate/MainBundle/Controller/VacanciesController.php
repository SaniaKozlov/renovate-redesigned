<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Vacancy;
use Renovate\MainBundle\Entity\Document;

class VacanciesController extends Controller
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
    	
    	return $this->render('RenovateMainBundle:Vacancies:index.html.twig', $parameters);
    }
    
    public function showVacancyAction($vacancy_name_translit)
    {
    	$em = $this->getDoctrine()->getManager();
    	$vacancy = $em->getRepository("RenovateMainBundle:Vacancy")->findOneByNameTranslit($vacancy_name_translit);
    	 
    	if ($vacancy == NULL) return $this->redirect($this->generateUrl('renovate_main_homepage'));

    	$parameters = array("vacancy" => $vacancy);
    	 
    	$parameters['page'] = $this->get('renovate.pages')->getPageForUrl($this->getRequest()->getUri());
    	
    	return $this->render('RenovateMainBundle:Vacancies:showVacancy.html.twig', $parameters);
    }
    
    public function getVacanciesNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Vacancy::getVacancies($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getVacanciesCountNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => Vacancy::getVacanciesCount($em))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function addVacancyNgAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	 
    	$transliterater = $this->get('renovate.transliterater');
    	$vacancy = Vacancy::addVacancy($em, $transliterater, $parameters);
    	 
    	$response = new Response(json_encode(array("result" => $vacancy->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    
    public function removeVacancyNgAction($vacancy_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$response = new Response(json_encode(array("result" => Vacancy::removeVacancyById($em, $vacancy_id))));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    
    public function editVacancyNgAction($vacancy_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$parameters = (object) $data;
    	 
    	$transliterater = $this->get('renovate.transliterater');
    	$vacancy = Vacancy::editVacancyById($em, $transliterater, $vacancy_id, $parameters);
    	 
    	$response = new Response(json_encode(array("result" => $vacancy->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
}
