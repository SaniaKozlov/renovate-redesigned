<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Project;

class ProjectsController extends Controller
{
    public function indexAction()
    {
    	return $this->render('RenovateMainBundle:Projects:index.html.twig');
    }
    
    public function getProjectsNgAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$response = new Response(json_encode(array("result" => Project::getProjects($em, $request->query->all(), true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }

    public function getProjectsCountNgAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $response = new Response(json_encode(array("result" => Project::getProjectsCount($em, $request->query->all()))));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    public function addProjectNgAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode(file_get_contents("php://input"));
        $parameters = (object) $data;

        $project = Project::addProject($em, $parameters);

        $response = new Response(json_encode(array("result" => $project->getInArray())));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    public function removeProjectNgAction($project_id)
    {
        $em = $this->getDoctrine()->getManager();

        $response = new Response(json_encode(array("result" => Project::removeProjectById($em, $project_id))));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    public function editProjectNgAction($project_id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode(file_get_contents("php://input"));
        $parameters = (object) $data;

        $project = Project::editProjectById($em, $project_id, $parameters);

        $response = new Response(json_encode(array("result" => $project->getInArray())));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function reportFullAction($project_id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository("RenovateMainBundle:Project")->find($project_id);
        $report = $project->generateReport();

        if (!$report) return new Response('Неможливо згенерувати звіт по проекту.');

        return $this->render('RenovateMainBundle:Projects:reportFull.html.twig', array('project' => $project, 'report' => $report));
    }

    public function reportAction($project_id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository("RenovateMainBundle:Project")->find($project_id);
        $report = $project->generateReport();

        if (!$report) return new Response('Неможливо згенерувати звіт по проекту.');

        return $this->render('RenovateMainBundle:Projects:report.html.twig', array('project' => $project, 'report' => $report));
    }
}
