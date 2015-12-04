<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Share
 *
 * @ORM\Table(name="shares")
 * @ORM\Entity
 */
class Share
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
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="shares")
     * @ORM\JoinColumn(name="documentid")
     * @var Document
     */
    private $document;
    
    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="sharesLabels")
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
     * @return Share
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
     * @return Share
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
     * @return Share
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
     * @return Share
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
     * @return Share
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
     * @return Share
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
     * @return Share
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
     * @return Share
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
     * @return Share
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
    
    public static function getAllShares($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Share")
    			 ->createQueryBuilder('s');
    	
    	$qb->select('s')
    	   ->orderBy('s.created', 'DESC');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($share){
    			return $share->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getShares($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Share")
    	->createQueryBuilder('s');
    	 
    	$qb->select('s')
    	   ->orderBy('s.created', 'DESC');
    	
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	
    	if (isset($parameters['onhomepage']))
    	{
    		$qb->where('s.onhomepage = :onhomepage')
    		->setParameter('onhomepage', $parameters['onhomepage']);
    	}
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($share){
    			return $share->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getSharesCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Share")
    				->createQueryBuilder('s')
    				->select('COUNT(s.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addShare($em, $transliterater, $parameters)
    {
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$share = new Share();
    	$share->setName($parameters->name);
    	$share->setNameTranslit($transliterater->transliterate($parameters->name));
    	$share->setDocumentid($parameters->documentid);
    	$share->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    	
    		$share->setLabelid($parameters->labelid);
    		$share->setLabel($label);
    	}
    	$share->setDescription($parameters->description);
    	$share->setCreated(new \DateTime());
    	$share->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($share);
    	$em->flush();
    	
    	return $share;
    }
    
    public static function removeShareById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Share', 's')
    	->where('s.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editShareById($em, $transliterater, $id, $parameters)
    {
    	$share = $em->getRepository("RenovateMainBundle:Share")->find($id);
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$share->setDocumentid($parameters->documentid);
    	$share->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    		 
    		$share->setLabelid($parameters->labelid);
    		$share->setLabel($label);
    	}
    	elseif ($share->getLabelid() != NULL && (!isset($parameters->labelid) || (isset($parameters->labelid) && $parameters->labelid == NULL)))
    	{
    		$oldLabel = $em->getRepository("RenovateMainBundle:Document")->find($share->getLabelid());
    	
    		$oldLabel->removeSharesLabel($share);
    		$share->setLabelid(NULL);
    		$share->setLabel(NULL);
    		$em->persist($oldLabel);
    	}
    	$share->setName($parameters->name);
    	$share->setNameTranslit($transliterater->transliterate($parameters->name));
    	$share->setDescription($parameters->description);
    	$share->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($share);
    	$em->flush();
    	
    	return $share;
    }
    
    public static function searchShares($em, $search, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Share")
    	->createQueryBuilder('s');
    
    	$qb->select('s')
    	->orderBy('s.created', 'DESC')
    	->where($qb->expr()->orX(
    			$qb->expr()->like('s.name', $qb->expr()->literal('%'.$search.'%')),
    			$qb->expr()->like('s.description', $qb->expr()->literal('%'.$search.'%'))
    	));
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($share){
    			return $share->getInArray();
    		}, $result);
    	}else return $result;
    }
}
