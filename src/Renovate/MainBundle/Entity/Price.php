<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table(name="prices")
 * @ORM\Entity
 */
class Price
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
     * @ORM\Column(name="categoryid", type="integer")
     */
    private $categoryid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="units", type="string", length=255)
     */
    private $units;
    
    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
    
    /**
     * @ORM\ManyToOne(targetEntity="PriceCategory", inversedBy="prices")
     * @ORM\JoinColumn(name="categoryid")
     * @var PriceCategory
     */
    private $category;
    
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
     * Set categoryid
     *
     * @param integer $categoryid
     * @return Price
     */
    public function setCategoryid($categoryid)
    {
    	$this->categoryid = $categoryid;
    
    	return $this;
    }
    
    /**
     * Get categoryid
     *
     * @return integer
     */
    public function getCategoryid()
    {
    	return $this->categoryid;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return Price
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
     * Set units
     *
     * @param string $units
     * @return Price
     */
    public function setUnits($units)
    {
    	$this->units = $units;
    
    	return $this;
    }
    
    /**
     * Get units
     *
     * @return string
     */
    public function getUnits()
    {
    	return $this->units;
    }
    
    /**
     * Set value
     *
     * @param float $value
     * @return Price
     */
    public function setValue($value)
    {
    	$this->value = $value;
    
    	return $this;
    }
    
    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
    	return $this->value;
    }
    
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Price
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
     * Set category
     *
     * @param \Renovate\MainBundle\Entity\PriceCategory $category
     * @return Price
     */
    public function setCategory(\Renovate\MainBundle\Entity\PriceCategory $category = null)
    {
    	$this->category = $category;
    
    	return $this;
    }
    
    /**
     * Get category
     *
     * @return \Renovate\MainBundle\Entity\PriceCategory
     */
    public function getCategory()
    {
    	return $this->category;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'categoryid' => $this->getCategoryid(),
    			'name' => $this->getName(),
    			'units' => $this->getUnits(),
    			'value' => $this->getValue(),
    			'created' => $this->getCreated()->getTimestamp()*1000
    	);
    }
    
    public static function getAllPrices($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Price")
    			 ->createQueryBuilder('p');
    	
    	$qb->select('p')
    	   ->orderBy('p.created', 'DESC');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($price){
    			return $price->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getPrices($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Price")
    	->createQueryBuilder('p');
    	 
    	$qb->select('p')
    	   ->orderBy('p.created', 'DESC');
    	
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		   ->setMaxResults($parameters['limit']);
    	}
    	   
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($price){
    			return $price->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getPricesCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Price")
    				->createQueryBuilder('p')
    				->select('COUNT(p.id)') 
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function addPrice($em, $parameters)
    {
    	$priceCategory = $em->getRepository("RenovateMainBundle:PriceCategory")->find($parameters->categoryid);
    	
    	$price = new Price();
    	$price->setName($parameters->name);
    	$price->setUnits($parameters->units);
    	$price->setValue($parameters->value);
    	$price->setCategoryid($priceCategory->getId());
    	$price->setCategory($priceCategory);
    	$price->setCreated(new \DateTime());
    	
    	$em->persist($price);
    	$em->flush();
    	
    	return $price;
    }
    
    public static function removePriceById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Price', 'p')
    	->where('p.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editPriceById($em, $id, $parameters)
    {
    	$price = $em->getRepository("RenovateMainBundle:Price")->find($id);
    	$priceCategory = $em->getRepository("RenovateMainBundle:PriceCategory")->find($parameters->categoryid);
    	
    	$price->setName($parameters->name);
    	$price->setUnits($parameters->units);
    	$price->setValue($parameters->value);
    	$price->setCategoryid($priceCategory->getId());
    	$price->setCategory($priceCategory);
    	
    	$em->persist($price);
    	$em->flush();
    	
    	return $price;
    }
}
