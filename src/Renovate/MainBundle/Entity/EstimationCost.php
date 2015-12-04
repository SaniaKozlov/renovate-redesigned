<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstimationCost
 *
 * @ORM\Table(name="estimations_costs")
 * @ORM\Entity
 */
class EstimationCost
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
     * @ORM\Column(name="estimationid", type="integer")
     */
    private $estimationid;

    /**
     * @var string
     *
     * @ORM\Column(name="category_type", type="string", length=255)
     */
    private $categoryType;
    
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
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="Estimation", inversedBy="estimationCosts")
     * @ORM\JoinColumn(name="estimationid")
     * @var EstimationCost
     */
    private $estimation;
    
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
     * Set estimationid
     *
     * @param integer $estimationid
     * @return EstimationCost
     */
    public function setEstimationid($estimationid)
    {
        $this->estimationid = $estimationid;

        return $this;
    }

    /**
     * Get estimationid
     *
     * @return integer 
     */
    public function getEstimationid()
    {
        return $this->estimationid;
    }

    /**
     * Set categoryType
     *
     * @param string $categoryType
     * @return EstimationCost
     */
    public function setCategoryType($categoryType)
    {
    	$this->categoryType = $categoryType;
    
    	return $this;
    }
    
    /**
     * Get categoryType
     *
     * @return string
     */
    public function getCategoryType()
    {
    	return $this->categoryType;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return EstimationCost
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
     * @return EstimationCost
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
     * @return EstimationCost
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
     * Set count
     *
     * @param integer $count
     * @return EstimationCost
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return EstimationCost
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }
    
    public function calculateTotal()
    {
    	$this->setTotal($this->getPrice()*$this->getCount());
    }

    /**
     * Set estimation
     *
     * @param \Renovate\MainBundle\Entity\Estimation $estimation
     * @return EstimationCost
     */
    public function setEstimation(\Renovate\MainBundle\Entity\Estimation $estimation = null)
    {
        $this->estimation = $estimation;

        return $this;
    }

    /**
     * Get estimation
     *
     * @return \Renovate\MainBundle\Entity\Estimation 
     */
    public function getEstimation()
    {
        return $this->estimation;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'estimationid' => $this->getEstimationid(),
    			'categoryType' => $this->getCategoryType(),
    			'name' => $this->getName(),
    			'units' => $this->getUnits(),
    			'price' => $this->getPrice(),
    			'count' => $this->getCount(),
    			'total' => $this->getTotal()
    	);
    }
    
    public static function addEstimationCost($em, $parameters)
    {
    	$estimation = $em->getRepository("RenovateMainBundle:Estimation")->find($parameters->estimationid);
    	$cost = $em->getRepository("RenovateMainBundle:Cost")->find($parameters->costid);
    	
    	$estimationCost = new EstimationCost();
    	$estimationCost->setEstimationid($estimation->getId());
    	$estimationCost->setEstimation($estimation);
    	
    	$estimationCost->setCategoryType($cost->getCategory()->getType());
    	$estimationCost->setName($cost->getName());
    	$estimationCost->setUnits($cost->getUnits());
    	$estimationCost->setPrice($cost->getPrice());
    	$estimationCost->setCount(1);
    	$estimationCost->setTotal($cost->getPrice());
    	
    	$em->persist($estimationCost);
    	$em->flush();
    	
    	$estimationCost->getEstimation()->calculateAmount();
    	
    	$em->persist($estimationCost->getEstimation());
    	$em->flush();
    
    	return $estimationCost;
    }
    
    public static function removeEstimationCostById($em, $id)
    {
    	$estimationCost = $em->getRepository("RenovateMainBundle:EstimationCost")->find($id);
    	
    	$em->remove($estimationCost);
    	$em->flush();
    	
    	$estimationCost->getEstimation()->calculateAmount();
    	 
    	$em->persist($estimationCost->getEstimation());
    	$em->flush();
    
    	return true;
    }
    
    public static function editEstimationCostById($em, $id, $parameters)
    {
    	$estimationCost = $em->getRepository("RenovateMainBundle:EstimationCost")->find($id);
    
    	$estimationCost->setName($parameters->name);
    	$estimationCost->setUnits($parameters->units);
    	$estimationCost->setPrice($parameters->price);
    	$estimationCost->setCount($parameters->count);
    	$estimationCost->calculateTotal();
    	$em->persist($estimationCost);
    	$em->flush();
    	
    	$estimationCost->getEstimation()->calculateAmount();
    	
    	$em->persist($estimationCost->getEstimation());
    	$em->flush();
    
    	return $estimationCost;
    }
}
