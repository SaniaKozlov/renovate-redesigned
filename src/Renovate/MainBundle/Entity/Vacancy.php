<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vacancy
 *
 * @ORM\Table(name="vacancies")
 * @ORM\Entity
 */
class Vacancy
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
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="vacancies")
     * @ORM\JoinColumn(name="documentid")
     * @var Document
     */
    private $document;
    
    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="vacanciesLabels")
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
     * @return Vacancy
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
     * @return Vacancy
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
     * @return Vacancy
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
     * @return Vacancy
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
     * @return Vacancy
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
     * @return Vacancy
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
     * @return Vacancy
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
     * @return Vacancy
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
     * @return Vacancy
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
    
    public static function getAllVacancies($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Vacancy")
    			 ->createQueryBuilder('v');
    	
    	$qb->select('v')
    	   ->orderBy('v.created', 'DESC');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($vacancy){
    			return $vacancy->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getVacancies($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Vacancy")
    	->createQueryBuilder('v');
    	 
    	$qb->select('v')
    	   ->orderBy('v.created', 'DESC');
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	 
    	if (isset($parameters['onhomepage']))
    	{
    		$qb->where('v.onhomepage = :onhomepage')
    		->setParameter('onhomepage', $parameters['onhomepage']);
    	}
    	
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($vacancy){
    			return $vacancy->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getVacanciesCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Vacancy")
    				->createQueryBuilder('v')
    				->select('COUNT(v.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addVacancy($em, $transliterater, $parameters)
    {
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$vacancy = new Vacancy();
    	$vacancy->setName($parameters->name);
    	$vacancy->setNameTranslit($transliterater->transliterate($parameters->name));
    	$vacancy->setDocumentid($parameters->documentid);
    	$vacancy->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    		 
    		$vacancy->setLabelid($parameters->labelid);
    		$vacancy->setLabel($label);
    	}
    	$vacancy->setDescription($parameters->description);
    	$vacancy->setCreated(new \DateTime());
    	$vacancy->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($vacancy);
    	$em->flush();
    	
    	return $vacancy;
    }
    
    public static function removeVacancyById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Vacancy', 'v')
    	->where('v.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editVacancyById($em, $transliterater, $id, $parameters)
    {
    	$vacancy = $em->getRepository("RenovateMainBundle:Vacancy")->find($id);
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$vacancy->setDocumentid($parameters->documentid);
    	$vacancy->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    		 
    		$vacancy->setLabelid($parameters->labelid);
    		$vacancy->setLabel($label);
    	}
    	elseif ($vacancy->getLabelid() != NULL && (!isset($parameters->labelid) || (isset($parameters->labelid) && $parameters->labelid == NULL)))
    	{
    		$oldLabel = $em->getRepository("RenovateMainBundle:Document")->find($vacancy->getLabelid());
    		 
    		$oldLabel->removeVacanciesLabel($vacancy);
    		$vacancy->setLabelid(NULL);
    		$vacancy->setLabel(NULL);
    		$em->persist($oldLabel);
    	}
    	$vacancy->setName($parameters->name);
    	$vacancy->setNameTranslit($transliterater->transliterate($parameters->name));
    	$vacancy->setDescription($parameters->description);
    	$vacancy->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($vacancy);
    	$em->flush();
    	
    	return $vacancy;
    }
    
    public static function searchVacancies($em, $search, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Vacancy")
    	->createQueryBuilder('v');
    	
    	$qb->select('v')
    	->orderBy('v.created', 'DESC')
    	->where($qb->expr()->orX(
       		$qb->expr()->like('v.name', $qb->expr()->literal('%'.$search.'%')),
       		$qb->expr()->like('v.description', $qb->expr()->literal('%'.$search.'%'))
   		));
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($vacancy){
    			return $vacancy->getInArray();
    		}, $result);
    	}else return $result;
    }
}
