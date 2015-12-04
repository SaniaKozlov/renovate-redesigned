<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estimation
 *
 * @ORM\Table(name="estimations")
 * @ORM\Entity
 */
class Estimation
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
     * @ORM\Column(name="estimation_number", type="string", length=255)
     */
    private $estimationNumber;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contract_number", type="string", length=255)
     */
    private $contractNumber;
    
    /**
     * @var string
     *
     * @ORM\Column(name="customer", type="string", length=255)
     */
    private $customer;

    /**
     * @var string
     *
     * @ORM\Column(name="performer", type="string", length=255)
     */
    private $performer;

    /**
     * @var float
     *
     * @ORM\Column(name="materials_amount", type="float")
     */
    private $materialsAmount;

    /**
     * @var float
     *
     * @ORM\Column(name="works_amount", type="float")
     */
    private $worksAmount;

    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float")
     */
    private $discount;
    
    /**
     * @var float
     *
     * @ORM\Column(name="total_amount", type="float")
     */
    private $totalAmount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="EstimationCost", mappedBy="estimation")
     * @var array
     */
    private $estimationCosts;

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
     * Set estimationNumber
     *
     * @param string $estimationNumber
     * @return Estimation
     */
    public function setEstimationNumber($estimationNumber)
    {
    	$this->estimationNumber = $estimationNumber;
    
    	return $this;
    }
    
    /**
     * Get estimationNumber
     *
     * @return string
     */
    public function getEstimationNumber()
    {
    	return $this->estimationNumber;
    }
    
    /**
     * Set contractNumber
     *
     * @param string $contractNumber
     * @return Estimation
     */
    public function setContractNumber($contractNumber)
    {
    	$this->contractNumber = $contractNumber;
    
    	return $this;
    }
    
    /**
     * Get contractNumber
     *
     * @return string
     */
    public function getContractNumber()
    {
    	return $this->contractNumber;
    }
    
    public function generateNumbers($em)
    {
    	$now = new \DateTime();
    	$from = $now->format('Y-m-00 00:00:00');
    	
    	$qb = $em->getRepository("RenovateMainBundle:Estimation")
    	->createQueryBuilder('e');
    	
    	$qb->select('COUNT(e.id)');
    	
    	$qb->andWhere('e.created > :from')
    	->setParameter('from', $from);
    	
    	$total = $qb->getQuery()->getSingleScalarResult();
    	
    	$number = $now->format('y').$now->format('m').sprintf("%03d",$total);
    	$this->setEstimationNumber('К'.$number);
    	$this->setContractNumber('Д'.$number);
    }
    
    /**
     * Set customer
     *
     * @param string $customer
     * @return Estimation
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return string 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set performer
     *
     * @param string $performer
     * @return Estimation
     */
    public function setPerformer($performer)
    {
        $this->performer = $performer;

        return $this;
    }

    /**
     * Get performer
     *
     * @return string 
     */
    public function getPerformer()
    {
        return $this->performer;
    }

    /**
     * Set materialsAmount
     *
     * @param float $materialsAmount
     * @return Estimation
     */
    public function setMaterialsAmount($materialsAmount)
    {
        $this->materialsAmount = $materialsAmount;

        return $this;
    }

    /**
     * Get materialsAmount
     *
     * @return float 
     */
    public function getMaterialsAmount()
    {
        return $this->materialsAmount;
    }

    /**
     * Set worksAmount
     *
     * @param float $worksAmount
     * @return Estimation
     */
    public function setWorksAmount($worksAmount)
    {
        $this->worksAmount = $worksAmount;

        return $this;
    }

    /**
     * Get worksAmount
     *
     * @return float 
     */
    public function getWorksAmount()
    {
        return $this->worksAmount;
    }

    /**
     * Set discount
     *
     * @param float $discount
     * @return Estimation
     */
    public function setDiscount($discount)
    {
    	$this->discount = $discount;
    
    	return $this;
    }
    
    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
    	return $this->discount;
    }
    
    /**
     * Set totalAmount
     *
     * @param float $totalAmount
     * @return Estimation
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return float 
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function calculateAmount()
    {
    	$worksAmount = 0;
    	$materialsAmount = 0;
    	
    	foreach ($this->getEstimationCosts() as $estimationCost){
    		switch ($estimationCost->getCategoryType()){
    			case "works": $worksAmount = $worksAmount + $estimationCost->getTotal(); break;
    			case "materials": $materialsAmount = $materialsAmount + $estimationCost->getTotal(); break;
    		}
    	}
    	
    	$this->setWorksAmount($worksAmount);
    	$this->setMaterialsAmount($materialsAmount);
    	$this->setTotalAmount($worksAmount+$materialsAmount-$this->getDiscount());
    	$this->setUpdated(new \DateTime());
    }
    
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Estimation
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Estimation
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    
    /**
     * Add estimationCosts
     *
     * @param \Renovate\MainBundle\Entity\EstimationCost $estimationCosts
     * @return Estimation
     */
    public function addEstimationCost(\Renovate\MainBundle\Entity\EstimationCost $estimationCosts)
    {
    	$this->estimationCosts[] = $estimationCosts;
    
    	return $this;
    }
    
    /**
     * Remove estimationCosts
     *
     * @param \Renovate\MainBundle\Entity\EstimationCost $estimationCosts
     */
    public function removeEstimationCost(\Renovate\MainBundle\Entity\EstimationCost $estimationCosts)
    {
    	$this->estimationCosts->removeElement($estimationCosts);
    }
    
    /**
     * Get estimationCosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstimationCosts()
    {
    	return $this->estimationCosts;
    }
    
    public function getCostCategories()
    {
    	$categories = array();
    	foreach ($this->estimationCosts as $estimationCost){
    		if (!in_array($estimationCost->getCategoryType(),array_map(function($category){ return $category['name'];},$categories))){
    			$categories[] = array('name' => $estimationCost->getCategoryType(), 'items' => array());
    		}	
    		
    		foreach ($categories as $key=>$category){
    			if ($category['name'] === $estimationCost->getCategoryType()){
    				array_push($categories[$key]['items'], $estimationCost);
    			}
    		}
    	}
    	
    	return $categories;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->estimationCosts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'estimationNumber' => $this->getEstimationNumber(),
    			'contractNumber' => $this->getContractNumber(),
    			'customer' => $this->getCustomer(),
    			'performer' => $this->getPerformer(),
    			'materialsAmount' => $this->getMaterialsAmount(),
    			'worksAmount' => $this->getWorksAmount(),
    			'discount' => $this->getDiscount(),
    			'totalAmount' => $this->getTotalAmount(),
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'updated' => $this->getUpdated()->getTimestamp()*1000,
    			'estimationCosts' => ($this->getEstimationCosts() == null ) ? array() : array_map(function($estimationCost){return $estimationCost->getInArray();}, $this->getEstimationCosts()->toArray())
    	);
    }
    
    public static function getEstimations($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Estimation")
    	->createQueryBuilder('e');
    
    	$qb->select('e')
    	->addOrderBy('e.updated', 'DESC');
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	
    	if (isset($parameters['from']))
    	{
    		$qb->andWhere('e.updated > :from')
    		->setParameter('from', $parameters['from']);
    	}
    	 
    	if (isset($parameters['to']))
    	{
    		$qb->andWhere('e.updated < :to')
    		->setParameter('to', $parameters['to']);
    	}
    	 
    	if (isset($parameters['search']))
    	{
    		$qb->andWhere($qb->expr()->orX(
    				$qb->expr()->like('e.id', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('e.customer', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('e.performer', $qb->expr()->literal('%'.$parameters['search'].'%'))
    		));
    	}
    	 
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($estimation){
    			return $estimation->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getEstimationsCount($em, $parameters)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Estimation")
    	->createQueryBuilder('e');
    	 
    	$qb->select('COUNT(e.id)');
    	 
    	if (isset($parameters['from']))
    	{
    		$qb->andWhere('e.updated > :from')
    		->setParameter('from', $parameters['from']);
    	}
    	 
    	if (isset($parameters['to']))
    	{
    		$qb->andWhere('e.updated < :to')
    		->setParameter('to', $parameters['to']);
    	}
    	 
    	if (isset($parameters['search']))
    	{
    		$qb->andWhere($qb->expr()->orX(
    				$qb->expr()->like('e.id', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('e.customer', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('e.performer', $qb->expr()->literal('%'.$parameters['search'].'%'))
    		));
    	}
    	 
    	$total = $qb->getQuery()->getSingleScalarResult();
    
    	return $total;
    }
    
    public static function saveEstimation($em, $parameters)
    {
    	if (isset($parameters->id)){
    		$estimation = $em->getRepository("RenovateMainBundle:Estimation")->find($parameters->id);
    		$estimation->setEstimationNumber($parameters->estimationNumber);
    		$estimation->setContractNumber($parameters->contractNumber);
    		$estimation->setDiscount($parameters->discount);
    		$estimation->calculateAmount();
    		$estimation->setUpdated(new \DateTime($parameters->updated));
    	}else{
    		$estimation = new Estimation();
    		$estimation->setMaterialsAmount(0);
    		$estimation->setWorksAmount(0);
    		$estimation->setDiscount(0);
    		$estimation->setTotalAmount(0);
    		$estimation->generateNumbers($em);
    		$estimation->setCreated(new \DateTime());
    		$estimation->setUpdated(new \DateTime());
    	}
    	
    	$estimation->setCustomer($parameters->customer);
    	$estimation->setPerformer($parameters->performer);
    	
    	$em->persist($estimation);
    	$em->flush();
    	
    	return $estimation;
    }
    
    public static function removeEstimationById($em, $id)
    {
    	$estimation = $em->getRepository("RenovateMainBundle:Estimation")->find($id);
    	
    	foreach($estimation->getEstimationCosts() as $estimationCost){
    		$em->remove($estimationCost);
    		$em->flush();
    	}
    	
    	$em->remove($estimation);
    	$em->flush();
    	
    	return true;
    }
    
    public static function copyEstimationById($em, $id)
    {
    	$estimation = $em->getRepository("RenovateMainBundle:Estimation")->find($id);

    	$estimationCopy = new Estimation();
    	$estimationCopy->setEstimationNumber($estimation->getEstimationNumber());
    	$estimationCopy->setContractNumber($estimation->getContractNumber());
    	$estimationCopy->setCustomer($estimation->getCustomer());
    	$estimationCopy->setPerformer($estimation->getPerformer());
    	$estimationCopy->setMaterialsAmount($estimation->getMaterialsAmount());
    	$estimationCopy->setWorksAmount($estimation->getWorksAmount());
    	$estimationCopy->setDiscount($estimation->getDiscount());
    	$estimationCopy->setTotalAmount($estimation->getTotalAmount());
    	$estimationCopy->setCreated(new \DateTime());
    	$estimationCopy->setUpdated(new \DateTime());
    	$em->persist($estimationCopy);
    	$em->flush();
    	
    	foreach($estimation->getEstimationCosts() as $estimationCost){
    		$estimationCostCopy = new EstimationCost();
    		$estimationCostCopy->setEstimationid($estimationCopy->getId());
    		$estimationCostCopy->setEstimation($estimationCopy);
    		$estimationCostCopy->setCategoryType($estimationCost->getCategoryType());
    		$estimationCostCopy->setName($estimationCost->getName());
    		$estimationCostCopy->setUnits($estimationCost->getUnits());
    		$estimationCostCopy->setPrice($estimationCost->getPrice());
    		$estimationCostCopy->setCount($estimationCost->getCount());
    		$estimationCostCopy->setTotal($estimationCost->getTotal());
    		$em->persist($estimationCostCopy);
    		$em->flush();
    	}
    	 
    	return $estimationCopy->getId();
    }
}
