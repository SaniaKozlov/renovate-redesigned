<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Job
 *
 * @ORM\Table(name="jobs")
 * @ORM\Entity
 */
class Job
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
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="jobs")
     * @ORM\JoinColumn(name="documentid")
     * @var Document
     */
    private $document;
    
    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="jobsLabels")
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
     * @return Job
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
     * @return Job
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
     * @return Job
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
     * @return Job
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
     * @return Job
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
     * @return Job
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
     * @return Job
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
     * @return Job
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
     * @return Job
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
    
    public static function getAllJobs($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Job")
    			 ->createQueryBuilder('j');
    	
    	$qb->select('j')
    	   ->orderBy('j.created', 'DESC');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($job){
    			return $job->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getJobs($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Job")
    	->createQueryBuilder('j');
    	 
    	$qb->select('j')
    	   ->orderBy('j.created', 'DESC');
    	
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		   ->setMaxResults($parameters['limit']);
    	}
    	   
    	if (isset($parameters['onhomepage']))
    	{
    		$qb->where('j.onhomepage = :onhomepage')
    		   ->setParameter('onhomepage', $parameters['onhomepage']);
    	}
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($job){
    			return $job->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getJobsCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Job")
    				->createQueryBuilder('j')
    				->select('COUNT(j.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addJob($em, $transliterater, $parameters)
    {
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$job = new Job();
    	$job->setName($parameters->name);
    	$job->setNameTranslit($transliterater->transliterate($parameters->name));
    	$job->setDocumentid($parameters->documentid);
    	$job->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    		 
    		$job->setLabelid($parameters->labelid);
    		$job->setLabel($label);
    	}
    	$job->setDescription($parameters->description);
    	$job->setCreated(new \DateTime());
    	$job->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($job);
    	$em->flush();
    	
    	return $job;
    }
    
    public static function removeJobById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Job', 'j')
    	->where('j.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editJobById($em, $transliterater, $id, $parameters)
    {
    	$job = $em->getRepository("RenovateMainBundle:Job")->find($id);
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$job->setDocumentid($parameters->documentid);
    	$job->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    		 
    		$job->setLabelid($parameters->labelid);
    		$job->setLabel($label);
    	}
    	elseif ($job->getLabelid() != NULL && (!isset($parameters->labelid) || (isset($parameters->labelid) && $parameters->labelid == NULL)))
    	{
    		$oldLabel = $em->getRepository("RenovateMainBundle:Document")->find($job->getLabelid());
    		
    		$oldLabel->removeJobsLabel($job);
    		$job->setLabelid(NULL);
    		$job->setLabel(NULL);
    		$em->persist($oldLabel);
    	}
    	$job->setName($parameters->name);
    	$job->setNameTranslit($transliterater->transliterate($parameters->name));
    	$job->setDescription($parameters->description);
    	$job->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($job);
    	$em->flush();
    	
    	return $job;
    }
    
    public static function searchJobs($em, $search, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Job")
    	->createQueryBuilder('j');
    	 
    	$qb->select('j')
    	->orderBy('j.created', 'DESC')
    	->where($qb->expr()->orX(
    			$qb->expr()->like('j.name', $qb->expr()->literal('%'.$search.'%')),
    			$qb->expr()->like('j.description', $qb->expr()->literal('%'.$search.'%'))
    	));
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($job){
    			return $job->getInArray();
    		}, $result);
    	}else return $result;
    }
}
