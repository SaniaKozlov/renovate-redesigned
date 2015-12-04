<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partner
 *
 * @ORM\Table(name="partners")
 * @ORM\Entity
 */
class Partner
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;
    
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
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="partners")
     * @ORM\JoinColumn(name="documentid")
     * @var Document
     */
    private $document;

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
     * @return Partner
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
     * Set name
     *
     * @param string $name
     * @return Partner
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
     * Set url
     *
     * @param string $url
     * @return Partner
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
     * Set created
     *
     * @param \DateTime $created
     * @return Partner
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
     * @return Partner
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
     * @return Partner
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
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'documentid' => $this->getDocumentid(),
    			'name' => $this->getName(),
    			'url' => $this->getUrl(),
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'onhomepage' => $this->getOnhomepage(),
    			'document' => $this->getDocument()->getInArray()
    	);
    }
    
    public static function getAllPartners($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Partner")
    			 ->createQueryBuilder('p');
    	
    	$qb->select('p')
    	   ->orderBy('p.created', 'DESC');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($partner){
    			return $partner->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getPartners($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Partner")
    	->createQueryBuilder('p');
    	 
    	$qb->select('p')
    	   ->orderBy('p.created', 'DESC');
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	 
    	if (isset($parameters['onhomepage']))
    	{
    		$qb->where('p.onhomepage = :onhomepage')
    		->setParameter('onhomepage', $parameters['onhomepage']);
    	}
    	
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($partner){
    			return $partner->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getPartnersCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Partner")
    				->createQueryBuilder('p')
    				->select('COUNT(p.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addPartner($em, $parameters)
    {
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$partner = new Partner();
    	$partner->setName($parameters->name);
    	$partner->setUrl($parameters->url);
    	$partner->setDocumentid($parameters->documentid);
    	$partner->setDocument($document);
    	$partner->setCreated(new \DateTime());
    	$partner->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($partner);
    	$em->flush();
    	
    	return $partner;
    }
    
    public static function removePartnerById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Partner', 'p')
    	->where('p.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editPartnerById($em, $id, $parameters)
    {
    	$partner = $em->getRepository("RenovateMainBundle:Partner")->find($id);
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$partner->setDocumentid($parameters->documentid);
    	$partner->setDocument($document);
    	$partner->setName($parameters->name);
    	$partner->setUrl($parameters->url);
    	$partner->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($partner);
    	$em->flush();
    	
    	return $partner;
    }
}
