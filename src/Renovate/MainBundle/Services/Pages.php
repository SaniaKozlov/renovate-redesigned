<?php
namespace Renovate\MainBundle\Services;

use Doctrine\ORM\Mapping as ORM;
use Renovate\MainBundle\Entity\Page;

class Pages
{
	private $em = null;
	
	public function __construct($em)
	{
		$this->em = $em;
	}
	
    public function getPageForUrl($url)
    {
    	return Page::findPageByUrl($this->em, $url);
    }
}