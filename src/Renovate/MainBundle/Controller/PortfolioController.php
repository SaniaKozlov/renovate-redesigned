<?php

namespace Renovate\MainBundle\Controller;

use Doctrine\ORM\EntityManager;
use Renovate\MainBundle\Entity\Portfolio;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PortfolioController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function portfolioAction()
    {
        /** @var Portfolio $portfolios */
        $portfolios = $this->getDoctrine()->getRepository('RenovateMainBundle:Portfolio')->findBy(array(), array('id' => 'DESC'));

        return $this->render("RenovateMainBundle:Portfolio:index.html.twig", array(
            'portfolios' => $portfolios
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addNewPortfolioAction(Request $request)
    {
        $file = $request->files->get('image');
        $extension = $file->guessExtension();
        $file_name = rand(100, 10000000000) . '.' . $extension;

        $portfolio = new Portfolio();
        $portfolio->setShowOnHomePage($request->request->get('showOnHomePage') == null ? false : true);
        $portfolio->setImage($file_name);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($portfolio);
        $em->flush();

        $file_path = __DIR__ . '/../../../../web/bundles/renovate/documents/portfolios/';
        $file->move($file_path, $file_name);

        return $this->redirect('/portfolio');
    }

    public function changeHomepageAction(Request $request, $portfolio_id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Portfolio $portfolio */
        $portfolio = $em->getRepository("RenovateMainBundle:Portfolio")->find($portfolio_id);

        $portfolio->setShowOnHomePage($request->request->get('showOnHome') == null ? false : true);

        $em->flush();

        return $this->redirect('/portfolio');
    }

}