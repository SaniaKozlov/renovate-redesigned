<?php
namespace Renovate\MainBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\EstimationCost;
class EstimationCostsController extends Controller
{
	public function addEstimationCostNgAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = json_decode(file_get_contents("php://input"));
		$parameters = (object) $data;

		$estimationCost = EstimationCost::addEstimationCost($em, $parameters);

		$response = new Response(json_encode(array("result" => ($estimationCost) ? $estimationCost->getInArray() : $estimationCost)));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	public function removeEstimationCostNgAction($estimationCost_id)
	{
		$em = $this->getDoctrine()->getManager();

		$response = new Response(json_encode(array("result" => EstimationCost::removeEstimationCostById($em, $estimationCost_id))));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	public function editEstimationCostNgAction($estimationCost_id)
	{
		$em = $this->getDoctrine()->getManager();
		$data = json_decode(file_get_contents("php://input"));
		$parameters = (object) $data;

		$estimationCost = EstimationCost::editEstimationCostById($em, $estimationCost_id, $parameters);

		$response = new Response(json_encode(array("result" => $estimationCost->getInArray())));
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
}