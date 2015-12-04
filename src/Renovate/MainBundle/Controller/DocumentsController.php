<?php
namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Renovate\MainBundle\Entity\Document;

class DocumentsController extends Controller
{
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		
		$timestamp = time();
		$token = Document::getToken($timestamp);
		 
		return $this->render('RenovateMainBundle:Documents:index.html.twig',
				array(
						'timestamp' => $timestamp,
						'token' => $token,
						'availableTypes'=> Document::getAvailableTypesInString()
				));
	}

	public function uploadAction(Request $request)
	{
		if ($request->getMethod() == 'POST') {
			
			$uploadedFile = $request->files->get('Filedata');

			$document = new Document();

			$timestamp = $request->request->get('timestamp');
			$token = $request->request->get('token');

			$document->setFile($uploadedFile, $timestamp, $token);

			if ($document->upload())
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($document);
				$em->flush();
				return new Response('Document uploaded!');
			}
			return new Response('Document NOT uploaded!');
		}else
			return new Response('Works only throw POST!');
	}

	public function getDocumentsNgAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		 
		$response = new Response(json_encode(array("result" => Document::getDocuments($em, $request->query->all(), true))));
		$response->headers->set('Content-Type', 'application/json');
	
		return $response;
	}
	
	public function getDocumentsCountNgAction()
	{
		$em = $this->getDoctrine()->getManager();
	
		$response = new Response(json_encode(array("result" => Document::getDocumentsCount($em))));
		$response->headers->set('Content-Type', 'application/json');
		 
		return $response;
	}
	
	public function removeDocumentNgAction($document_id)
	{
		$em = $this->getDoctrine()->getManager();
		 
		$response = new Response(json_encode(array("result" => Document::removeDocumentById($em, $document_id))));
		$response->headers->set('Content-Type', 'application/json');
		 
		return $response;
	}
}