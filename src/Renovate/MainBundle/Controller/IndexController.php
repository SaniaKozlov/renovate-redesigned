<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Renovate\MainBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Renovate\MainBundle\Entity\News;
use Renovate\MainBundle\Entity\Job;
use Renovate\MainBundle\Entity\Result;
use Renovate\MainBundle\Entity\Article;
use Renovate\MainBundle\Entity\Share;
use Renovate\MainBundle\Entity\Vacancy;
use Renovate\MainBundle\Entity\Portfolio;

class IndexController extends Controller
{
    public function indexAction(Request $request)
    {
    	$session = $request->getSession();
    	//get the login error if there is one
    	if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
    		$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    	}elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR))
    	{
    		$error = $session->get(SecurityContext::AUTHENTICATION_ERROR)->getMessage();
    		$session->remove(SecurityContext::AUTHENTICATION_ERROR);
    	}else {
    		$error = '';
    	}
    	 
    	//last username entered by the user
    	$lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
    	
    	$parameters = array(
    			'last_username' => $lastUsername,
        		'error' => $error);
    	
    	$parameters['page'] = $this->get('renovate.pages')->getPageForUrl($this->getRequest()->getUri());
    	
    	$em = $this->getDoctrine()->getManager();
    	$parameters['lastNews'] = News::getNews($em, array('offset' => 0,'limit' => 3));
		$parameters['lastNews1'] = News::getNews($em, array('offset' => 3,'limit' => 3));
		$jobs = Job::getJobs($em, array('onhomepage' => 1));
		foreach ($jobs as $job){
			$job->setDescription(strip_tags($job->getDescription()));;
			$job->setShortDescription(strip_tags($job->getShortDescription()));
		}
    	$parameters['jobs'] = $jobs;
    	$parameters['results'] = Result::getResults($em, array('onhomepage' => 1));
    	$parameters['news'] = News::getNews($em, array('onhomepage' => 1));
    	$parameters['articles'] = Article::getArticles($em, array('onhomepage' => 1));
    	$parameters['shares'] = Share::getShares($em, array('onhomepage' => 1));
    	$parameters['vacancies'] = Vacancy::getVacancies($em, array('onhomepage' => 1));
		$parameters['results'] = $this->getDoctrine()->getRepository('RenovateMainBundle:Result')->findBy(array('onhomepage' => true));
        return $this->render('RenovateMainBundle:Index:index.html.twig', $parameters);
    }
}
