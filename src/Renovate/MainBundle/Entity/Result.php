<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Result
 *
 * @ORM\Table(name="results")
 * @ORM\Entity
 */
class Result
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
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="results")
     * @ORM\JoinColumn(name="documentid")
     * @var Document
     */
    private $document;
    
    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="resultsLabels")
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
     * @return Result
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
     * @return Result
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
     * @return Result
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
     * @return Result
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
     * @return Result
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
     * @return Result
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
     * @return Result
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
     * @return Result
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
     * @return Result
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
    
    public static function getAllResults($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Result")
    			 ->createQueryBuilder('r');
    	
    	$qb->select('r')
    	   ->orderBy('r.created', 'DESC');
    	
    	$results = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($result){
    			return $result->getInArray();
    		}, $results);
    	}else return $results;
    }
    
    public static function getResults($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Result")
    	->createQueryBuilder('r');
    	 
    	$qb->select('r')
    	   ->orderBy('r.created', 'DESC');
    	
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	
    	if (isset($parameters['onhomepage']))
    	{
    		$qb->where('r.onhomepage = :onhomepage')
    		->setParameter('onhomepage', $parameters['onhomepage']);
    	}
    	 
    	$results = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($result){
    			return $result->getInArray();
    		}, $results);
    	}else return $results;
    }
    
    public static function getResultsCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Result")
    				->createQueryBuilder('r')
    				->select('COUNT(r.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addResult($em, $transliterater, $parameters)
    {
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$result = new Result();
    	$result->setName($parameters->name);
    	$result->setNameTranslit($transliterater->transliterate($parameters->name));
    	$result->setDocumentid($parameters->documentid);
    	$result->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    	
    		$result->setLabelid($parameters->labelid);
    		$result->setLabel($label);
    	}
    	$result->setDescription($parameters->description);
    	$result->setCreated(new \DateTime());
    	$result->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($result);
    	$em->flush();
    	
    	return $result;
    }
    
    public static function removeResultById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Result', 'r')
    	->where('r.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editResultById($em, $transliterater, $id, $parameters)
    {
    	$result = $em->getRepository("RenovateMainBundle:Result")->find($id);
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$result->setDocumentid($parameters->documentid);
    	$result->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    		 
    		$result->setLabelid($parameters->labelid);
    		$result->setLabel($label);
    	}
    	elseif ($result->getLabelid() != NULL && (!isset($parameters->labelid) || (isset($parameters->labelid) && $parameters->labelid == NULL)))
    	{
    		$oldLabel = $em->getRepository("RenovateMainBundle:Document")->find($result->getLabelid());
    	
    		$oldLabel->removeResultsLabel($result);
    		$result->setLabelid(NULL);
    		$result->setLabel(NULL);
    		$em->persist($oldLabel);
    	}
    	$result->setName($parameters->name);
    	$result->setNameTranslit($transliterater->transliterate($parameters->name));
    	$result->setDescription($parameters->description);
    	$result->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($result);
    	$em->flush();
    	
    	return $result;
    }
    
    public static function searchResults($em, $search, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Result")
    	->createQueryBuilder('r');
    
    	$qb->select('r')
    	->orderBy('r.created', 'DESC')
    	->where($qb->expr()->orX(
    			$qb->expr()->like('r.name', $qb->expr()->literal('%'.$search.'%')),
    			$qb->expr()->like('r.description', $qb->expr()->literal('%'.$search.'%'))
    	));
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($res){
    			return $res->getInArray();
    		}, $result);
    	}else return $result;
    }
}
