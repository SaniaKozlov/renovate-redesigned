<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Renovate\MainBundle\Entity\Article;
use Renovate\MainBundle\Entity\Job;
use Renovate\MainBundle\Entity\News;
use Renovate\MainBundle\Entity\Result;
use Renovate\MainBundle\Entity\Share;
use Renovate\MainBundle\Entity\Vacancy;

class SearchController extends Controller
{
    public function indexAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$parameters = array();
    	
    	if ($request->isMethod('POST')){
    		if ($request->request->get('search'))
    		{
    			$articles = Article::searchArticles($em, $request->request->get('search'));
    			$parameters['articles'] = $articles;
    			$jobs = Job::searchJobs($em, $request->request->get('search'));
    			$parameters['jobs'] = $jobs;
    			$news = News::searchNews($em, $request->request->get('search'));
    			$parameters['news'] = $news;
    			$results = Result::searchResults($em, $request->request->get('search'));
    			$parameters['results'] = $results;
    			$shares = Share::searchShares($em, $request->request->get('search'));
    			$parameters['shares'] = $shares;
    			$vacancies = Vacancy::searchVacancies($em, $request->request->get('search'));
    			$parameters['vacancies'] = $vacancies;
    		}
    		
    		$parameters['search'] = $request->request->get('search');
    	}
    	
    	
    	return $this->render('RenovateMainBundle:Search:index.html.twig', $parameters);
    }
}
