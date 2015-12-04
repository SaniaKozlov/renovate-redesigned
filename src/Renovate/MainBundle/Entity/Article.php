<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity
 */
class Article
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
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="articles")
     * @ORM\JoinColumn(name="documentid")
     * @var Document
     */
    private $document;
    
    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="articlesLabels")
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
    
    public static function getAllArticles($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Article")
    			 ->createQueryBuilder('a');
    	
    	$qb->select('a')
    	   ->orderBy('a.created', 'DESC');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($article){
    			return $article->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getArticles($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Article")
    	->createQueryBuilder('a');
    	 
    	$qb->select('a')
    	   ->orderBy('a.created', 'DESC');
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	 
    	if (isset($parameters['onhomepage']))
    	{
    		$qb->where('a.onhomepage = :onhomepage')
    		->setParameter('onhomepage', $parameters['onhomepage']);
    	}
    	
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($article){
    			return $article->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getArticlesCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Article")
    				->createQueryBuilder('a')
    				->select('COUNT(a.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addArticle($em, $transliterater, $parameters)
    {
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$article = new Article();
    	$article->setName($parameters->name);
    	$article->setNameTranslit($transliterater->transliterate($parameters->name));
    	$article->setDocumentid($parameters->documentid);
    	$article->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    		 
    		$article->setLabelid($parameters->labelid);
    		$article->setLabel($label);
    	}
    	$article->setDescription($parameters->description);
    	$article->setCreated(new \DateTime());
    	$article->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($article);
    	$em->flush();
    	
    	return $article;
    }
    
    public static function removeArticleById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Article', 'a')
    	->where('a.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editArticleById($em, $transliterater, $id, $parameters)
    {
    	$article = $em->getRepository("RenovateMainBundle:Article")->find($id);
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($parameters->documentid);
    	
    	$article->setDocumentid($parameters->documentid);
    	$article->setDocument($document);
    	if (isset($parameters->labelid) && $parameters->labelid != NULL)
    	{
    		$label = $em->getRepository("RenovateMainBundle:Document")->find($parameters->labelid);
    		 
    		$article->setLabelid($parameters->labelid);
    		$article->setLabel($label);
    	}
    	elseif ($article->getLabelid() != NULL && (!isset($parameters->labelid) || (isset($parameters->labelid) && $parameters->labelid == NULL)))
    	{
    		$oldLabel = $em->getRepository("RenovateMainBundle:Document")->find($article->getLabelid());
    		 
    		$oldLabel->removeArticlesLabel($article);
    		$article->setLabelid(NULL);
    		$article->setLabel(NULL);
    		$em->persist($oldLabel);
    	}
    	$article->setName($parameters->name);
    	$article->setNameTranslit($transliterater->transliterate($parameters->name));
    	$article->setDescription($parameters->description);
    	$article->setOnhomepage($parameters->onhomepage);
    	
    	$em->persist($article);
    	$em->flush();
    	
    	return $article;
    }
    
    public static function searchArticles($em, $search, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Article")
    	->createQueryBuilder('a');
    	
    	$qb->select('a')
    	->orderBy('a.created', 'DESC')
    	->where($qb->expr()->orX(
       		$qb->expr()->like('a.name', $qb->expr()->literal('%'.$search.'%')),
       		$qb->expr()->like('a.description', $qb->expr()->literal('%'.$search.'%'))
   		));
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($article){
    			return $article->getInArray();
    		}, $result);
    	}else return $result;
    }
}
