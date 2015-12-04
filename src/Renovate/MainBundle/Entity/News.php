<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity
 */
class News
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
     * @var integer
     *
     * @ORM\Column(name="documentid", type="integer")
     */
    private $documentid;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="labelid", type="integer")
     */
    private $labelid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name_translit", type="string", length=255)
     */
    private $nameTranslit;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var boolean
     * @ORM\Column(name="onhomepage", type="boolean")
     */
    private $onhomepage;
    
    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="news")
     * @ORM\JoinColumn(name="documentid")
     * @var Document
     */
    private $document;
    
    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="newsLabels")
     * @ORM\JoinColumn(name="labelid")
     * @var Document
     */
    private $label;

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
     * Set documentid
     *
     * @param integer $documentid
     * @return News
     */
    public function setDocumentid($documentid)
    {
    	$this->documentid = $documentid;
    
    	return $this;
    }
    
    /**
     * Get documentid
     *
     * @return integer
     */
    public function getDocumentid()
    {
    	return $this->documentid;
    }

    /**
     * Set labelid
     *
     * @param integer $labelid
     * @return News
     */
    public function setLabelid($labelid)
    {
    	$this->labelid = $labelid;
    
    	return $this;
    }
    
    /**
     * Get labelid
     *
     * @return integer
     */
    public function getLabelid()
    {
    	return $this->labelid;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return News
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nameTranslit
     *
     * @param string $nameTranslit
     * @return News
     */
    public function setNameTranslit($nameTranslit)
    {
    	$this->nameTranslit = $nameTranslit;
    
    	return $this;
    }
    
    /**
     * Get nameTranslit
     *
     * @return string
     */
    public function getNameTranslit()
    {
    	return $this->nameTranslit;
    }
    
    /**
     * Set description
     *
     * @param string $description
     * @return News
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
     * Set created
     *
     * @param \DateTime $created
     * @return News
     */
    public function setCreated($created)
    {
    	$this->created = $created;
    
    	return $this;
    }
    
    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
    	return $this->created;
    }
    
    /**
     * Set onhomepage
     *
     * @param boolean $onhomepage
     * @return News
     */
    public function setOnhomepage($onhomepage)
    {
    	$this->onhomepage = $onhomepage;
    
    	return $this;
    }
    
    /**
     * Get onhomepage
     *
     * @return boolean
     */
    public function getOnhomepage()
    {
    	return $this->onhomepage;
    }
    
    /**
     * Set document
     *
     * @param \Renovate\MainBundle\Entity\Document $document
     * @return News
     */
    public function setDocument(\Renovate\MainBundle\Entity\Document $document = null)
    {
    	$this->document = $document;
    
    	return $this;
    }
    
    /**
     * Get document
     *
     * @return \Renovate\MainBundle\Entity\Document
     */
    public function getDocument()
    {
    	return $this->document;
    }
    
    /**
     * Set label
     *
     * @param \Renovate\MainBundle\Entity\Document $label
     * @return News
     */
    public function setLabel(\Renovate\MainBundle\Entity\Document $label = null)
    {
    	$this->label = $label;
    
    	return $this;
    }
    
    /**
     * Get label
     *
     * @return \Renovate\MainBundle\Entity\Document
     */
    public function getLabel()
    {
    	return $this->label;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'documentid' => $this->getDocumentid(),
    			'labelid' => $this->getLabelid(),
    			'name' => $this->getName(),
    			'nameTranslit' => $this->getNameTranslit(),
    			'description' => $this->getDescription(),
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'onhomepage' => $this->getOnhomepage(),
    			'document' => $this->getDocument()->getInArray(),
    			'label' => ($this->getLabel() != null) ? $this->getLabel()->getInArray() : null
    	);
    }
    
    public static function getAllNews($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:News")
    			 ->createQueryBuilder('n');
    	
    	$qb->select('n')
    	   ->orderBy('n.created', 'DESC');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($news){
    			return $news->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getNews($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:News")
    	->createQueryBuilder('n');
    	 
    	$qb->select('n')
    	   ->orderBy('n.created', 'DESC');
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	 
    	if (isset($parameters['onhomepage']))
    	{
    		$qb->where('n.onhomepage = :onhomepage')
    		->setParameter('onhomepage', $parameters['onhomepage']);
    	}
    	
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($news){
    			return $news->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getNewsCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:News")
    				->createQueryBuilder('n')
    				->select('COUNT(n.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addNews($em, $transliterater, $parameters)
    {
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$news = new News();
    	$news->setName($parameters->name);
    	$news->setNameTranslit($transliterater->transliterate($parameters->name));
    	$news->setDocumentid($parameters->documentid);
    	$news->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    		
    		$news->setLabelid($parameters->labelid);
    		$news->setLabel($label);
    	}
    	$news->setDescription($parameters->description);
    	$news->setCreated(new \DateTime());
    	$news->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($news);
    	$em->flush();
    	
    	return $news;
    }
    
    public static function removeNewsById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:News', 'n')
    	->where('n.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editNewsById($em, $transliterater, $id, $parameters)
    {
    	$news = $em->getRepository("RenovateMainBundle:News")->find($id);
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$news->setDocumentid($parameters->documentid);
    	$news->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    		 
    		$news->setLabelid($parameters->labelid);
    		$news->setLabel($label);
    	}
    	elseif ($news->getLabelid() != NULL && (!isset($parameters->labelid) || (isset($parameters->labelid) && $parameters->labelid == NULL)))
    	{
    		$oldLabel = $em->getRepository("RenovateMainBundle:Document")->find($news->getLabelid());
    		
    		$oldLabel->removeNewsLabel($news);
    		$news->setLabelid(NULL);
    		$news->setLabel(NULL);
    		$em->persist($oldLabel);
    	}
    	$news->setName($parameters->name);
    	$news->setNameTranslit($transliterater->transliterate($parameters->name));
    	$news->setDescription($parameters->description);
    	$news->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($news);
    	$em->flush();
    	
    	return $news;
    }
    
    public static function searchNews($em, $search, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:News")
    	->createQueryBuilder('n');
    
    	$qb->select('n')
    	->orderBy('n.created', 'DESC')
    	->where($qb->expr()->orX(
    			$qb->expr()->like('n.name', $qb->expr()->literal('%'.$search.'%')),
    			$qb->expr()->like('n.description', $qb->expr()->literal('%'.$search.'%'))
    	));
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($news){
    			return $news->getInArray();
    		}, $result);
    	}else return $result;
    }
}
