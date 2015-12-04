<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends Controller
{
    public function showExceptionAction($exception, $logger)
    {
    	return $this->render('RenovateMainBundle:Exception:showException.html.twig', array('exception'=>$exception));
    }
}
