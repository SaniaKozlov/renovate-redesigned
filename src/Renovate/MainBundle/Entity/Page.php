<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity
 */
class Page
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text")
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text")
     */
    private $keywords;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
    	return $this->id;
    }
    
    /**
     * Set url
     *
     * @param string $url
     * @return Page
     */
    public function setUrl($url)
    {
    	$this->url = $url;
    
    	return $this;
    }
    
    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
    	return $this->url;
    }
    
    /**
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
    	$this->title = $title;
    
    	return $this;
    }
    
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
    	return $this->title;
    }
    
    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Page
     */
    public function setKeywords($keywords)
    {
    	$this->keywords = $keywords;
    
    	return $this;
    }
    
    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
    	return $this->keywords;
    }
    
    /**
     * Set description
     *
     * @param string $description
     * @return Page
     */
    public function setDescription($description)
    {
    	$this->description = $description;
    
    	return $this;
    }
    
    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
    	return $this->description;
    }
    
    /**
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content)
    {
    	$this->content = $content;
    
    	return $this;
    }
    
    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
    	return $this->content;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'url' => $this->getUrl(),
    			'title' => $this->getTitle(),
    			'keywords' => $this->getKeywords(),
    			'description' => $this->getDescription(),
    			'content' => $this->getContent()
    	);
    }
    
    public static function getAllPages($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Page")
    	->createQueryBuilder('p');
    	 
    	$qb->select('p')
    	   ->orderBy('p.id', 'DESC');
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($page){
    			return $page->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getPages($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Page")
    	->createQueryBuilder('p');
    
    	$qb->select('p')
    	   ->orderBy('p.id', 'DESC');
    
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($page){
    			return $page->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getPagesCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Page")
    	->createQueryBuilder('p')
    	->select('COUNT(p.id)')
    	->getQuery();
    	 
    	$total = $query->getSingleScalarResult();
    	 
    	return $total;
    }
    
    public static function addPage($em, $parameters)
    {
    	$page = new Page();
    	$page->setUrl($parameters->url);
    	
    	if (isset($parameters->title))
    		$page->setTitle($parameters->title);
    	
    	if (isset($parameters->keywords))
    		$page->setKeywords($parameters->keywords);
    	
    	if (isset($parameters->description))
    		$page->setDescription($parameters->description);
    	
    	if (isset($parameters->content))
    		$page->setContent($parameters->content);
    	
    	$em->persist($page);
    	$em->flush();
    	 
    	return $page;
    }
    
    public static function removePageById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    
    	$qb->delete('RenovateMainBundle:Page', 'p')
    	->where('p.id = :id')
    	->setParameter('id', $id);
    
    	return $qb->getQuery()->getResult();
    }
    
    public static function editPageById($em, $id, $parameters)
    {
    	$page = $em->getRepository("RenovateMainBundle:Page")->find($id);
    	 
    	$page->setUrl($parameters->url);
    	
    	(isset($parameters->title))
    		? $page->setTitle($parameters->title)
    		: $page->setTitle('');
    	
    	(isset($parameters->keywords))
    		? $page->setKeywords($parameters->keywords)
    		: $page->setKeywords('');
    	
    	(isset($parameters->description))
    		? $page->setDescription($parameters->description)
    		: $page->setDescription('');
    	
    	(isset($parameters->content))
    		? $page->setContent($parameters->content)
    		: $page->setContent('');
    	 
    	$em->persist($page);
    	$em->flush();
    	 
    	return $page;
    }
    
    public static function findPageByUrl($em, $url)
    {
    	return $em->getRepository("RenovateMainBundle:Page")->findOneByUrl($url);
    }
}
