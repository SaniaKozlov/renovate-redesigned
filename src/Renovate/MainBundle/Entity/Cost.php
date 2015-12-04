<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cost
 *
 * @ORM\Table(name="costs")
 * @ORM\Entity
 */
class Cost
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
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="CostCategory", inversedBy="costs")
     * @ORM\JoinColumn(name="categoryid")
     * @var CostCategory
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
     * @return Cost
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
     * @return Cost
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
     * @return Cost
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
     * Set price
     *
     * @param float $price
     * @return Cost
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set category
     *
     * @param \Renovate\MainBundle\Entity\CostCategory $category
     * @return Cost
     */
    public function setCategory(\Renovate\MainBundle\Entity\CostCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Renovate\MainBundle\Entity\CostCategory 
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
    			'categoryType' => $this->getCategory()->getType(),
    			'name' => $this->getName(),
    			'units' => $this->getUnits(),
    			'price' => $this->getPrice()
    	);
    }
    
    public static function addCost($em, $parameters)
    {
    	$costCategory = $em->getRepository("RenovateMainBundle:CostCategory")->find($parameters->categoryid);
    	 
    	$cost = new Cost();
    	$cost->setCategoryid($costCategory->getId());
    	$cost->setCategory($costCategory);
    	$cost->setName($parameters->name);
    	$cost->setUnits($parameters->units);
    	$cost->setPrice($parameters->price);
    
    	$em->persist($cost);
    	$em->flush();
    
    	return $cost;
    }
    
    public static function editCostById($em, $id, $parameters)
    {
    	$cost = $em->getRepository("RenovateMainBundle:Cost")->find($id);
    
    	$cost->setName($parameters->name);
    	$cost->setUnits($parameters->units);
    	$cost->setPrice($parameters->price);
    
    	$em->persist($cost);
    	$em->flush();
    
    	return $cost;
    }
    
    public static function removeCostById($em, $id)
    {
    	$cost = $em->getRepository("RenovateMainBundle:Cost")->find($id);
    
    	$em->remove($cost);
    	$em->flush();
    
    	return true;
    }
    
    public static function filterCosts($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Cost")
    	->createQueryBuilder('c');
    
    	$qb->select('c')
    	->join("c.category", "cc")
    	->andWhere("cc.type = :type")
    	->setParameter('type', $parameters['type'])
    	->addOrderBy('c.name');
    	
    	if (isset($parameters['filter']))
    	{
    		$qb->andWhere($qb->expr()->orX(
    				$qb->expr()->like('c.name', $qb->expr()->literal('%'.$parameters['filter'].'%'))
    		));
    	}
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($cost){
    			return $cost->getInArray();
    		}, $result);
    	}else return $result;
    }
}
