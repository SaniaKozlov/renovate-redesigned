<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PriceCategory
 *
 * @ORM\Table(name="price_categories")
 * @ORM\Entity
 */
class PriceCategory
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;
    
    /**
     * @ORM\OneToMany(targetEntity="Price", mappedBy="category")
     * @var array
     */
    private $prices;

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
     * Set name
     *
     * @param string $name
     * @return PriceCategory
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
     * Set level
     *
     * @param integer $level
     * @return PriceCategory
     */
    public function setLevel($level)
    {
    	$this->level = $level;
    
    	return $this;
    }
    
    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
    	return $this->level;
    }
    
    /**
     * Add prices
     *
     * @param \Renovate\MainBundle\Entity\Price $prices
     * @return PriceCategory
     */
    public function addPrice(\Renovate\MainBundle\Entity\Price $prices)
    {
    	$this->prices[] = $prices;
    
    	return $this;
    }
    
    /**
     * Remove prices
     *
     * @param \Renovate\MainBundle\Entity\Price $prices
     */
    public function removePrice(\Renovate\MainBundle\Entity\Price $prices)
    {
    	$this->prices->removeElement($prices);
    }
    
    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrices()
    {
    	return $this->prices;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->prices = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'name' => $this->getName(),
    			'level' => $this->getLevel(),
    			'prices' => array_map(function($price){return $price->getInArray();}, $this->getPrices()->toArray())
    	);
    }
    
    public static function getAllPriceCategories($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:PriceCategory")
    	->createQueryBuilder('p');
    
    	$qb->select('p')
    	   ->orderBy('p.level', 'DESC');
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($priceCategory){
    			return $priceCategory->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function addPriceCategory($em, $parameters)
    {
    	$priceCategory = new PriceCategory();
    	$priceCategory->setName($parameters->name);
    	$priceCategory->setLevel($parameters->level);
    	 
    	$em->persist($priceCategory);
    	$em->flush();
    	 
    	return $priceCategory;
    }
    
    public static function editPriceCategoryById($em, $id, $parameters)
    {
    	$priceCategory = $em->getRepository("RenovateMainBundle:PriceCategory")->find($id);
    	 
    	$priceCategory->setName($parameters->name);
    	$priceCategory->setLevel($parameters->level);
    	 
    	$em->persist($priceCategory);
    	$em->flush();
    	 
    	return $priceCategory;
    }
    
    public static function removePriceCategoryById($em, $id)
    {
    	$priceCategory = $em->getRepository("RenovateMainBundle:PriceCategory")->find($id);
    	
    	foreach($priceCategory->getPrices() as $price){
    			$em->remove($price);
    			$em->flush();
    	}
    	
    	$em->remove($priceCategory);
    	$em->flush();
    
    	return true;
    }
}
