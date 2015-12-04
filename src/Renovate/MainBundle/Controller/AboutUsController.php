<?php

namespace Renovate\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutUsController extends Controller
{
    public function indexAction()
    {
        $parameters = array();
        $parameters['page'] = $this->get('renovate.pages')->getPageForUrl($this->getRequest()->getUri());

        return $this->render('RenovateMainBundle:AboutUs:index.html.twig', $parameters);
    }
}
