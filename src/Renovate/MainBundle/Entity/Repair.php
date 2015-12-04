<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Repair
 *
 * @ORM\Table(name="repairs")
 * @ORM\Entity
 */
class Repair
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
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;
    
    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
    
    /**
     * @var boolean
     * @ORM\Column(name="paid", type="boolean")
     */
    private $paid;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="repairsCreated")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
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
     * Set userid
     *
     * @param integer $userid
     * @return Repair
     */
    public function setUserid($userid)
    {
    	$this->userid = $userid;
    
    	return $this;
    }
    
    /**
     * Get userid
     *
     * @return integer
     */
    public function getUserid()
    {
    	return $this->userid;
    }
    
    
    
    
    
    /**
     * Set description
     *
     * @param string $description
     * @return Repair
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
     * Set price
     *
     * @param float $price
     * @return Repair
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
     * Set created
     *
     * @param \DateTime $created
     * @return Repair
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
     * Set paid
     *
     * @param boolean $paid
     * @return Repair
     */
    public function setPaid($paid)
    {
    	$this->paid = $paid;
    
    	return $this;
    }
    
    /**
     * Get paid
     *
     * @return boolean
     */
    public function getPaid()
    {
    	return $this->paid;
    }
    
    /**
     * Set user
     *
     * @param \Renovate\MainBundle\Entity\User $user
     * @return Repair
     */
    public function setUser(\Renovate\MainBundle\Entity\User $user = null)
    {
    	$this->user = $user;
    
    	return $this;
    }
    
    /**
     * Get user
     *
     * @return \Renovate\MainBundle\Entity\User
     */
    public function getUser()
    {
    	return $this->user;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'userid' => $this->getUserid(),
    			'description' => $this->getDescription(),
    			'price' => $this->getPrice(),
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'paid' => $this->getPaid(),
    			'user' => $this->getUser()->getInArray()
    	);
    }
    
    public static function getAllRepairs($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Repair")
    			 ->createQueryBuilder('r');
    	
    	$qb->select('r')
    	   ->orderBy('r.created', 'DESC');
    	
    	$result = $qb->getQuery()->getResult();
    	
    	if ($inArray)
    	{
    		return array_map(function($repair){
    			return $repair->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getRepairs($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Repair")
    	->createQueryBuilder('r');
    	 
    	$qb->select('r')
    	   ->orderBy('r.created', 'DESC');
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	
    	if (isset($parameters['from']))
    	{
    		$qb->andWhere('r.created > :from')
    		->setParameter('from', $parameters['from']);
    	}
    	 
    	if (isset($parameters['to']))
    	{
    		$qb->andWhere('r.created < :to')
    		->setParameter('to', $parameters['to']);
    	}
    	 
    	if (isset($parameters['userid']))
    	{
    		$qb->andWhere("r.userid = :id")
    		->setParameter("id", $parameters['userid']);
    	}
    	
    	if (isset($parameters['paid']))
    	{
    		$qb->andWhere("r.paid = :paid")
    		->setParameter("paid", $parameters['paid']);
    	}
    	
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($repair){
    			return $repair->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getRepairsCount($em, $parameters)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Repair")
    			 ->createQueryBuilder('r');
    	
    	$qb->select('COUNT(r.id)');
    	
    	if (isset($parameters['from']))
    	{
    		$qb->andWhere('r.created > :from')
    		   ->setParameter('from', $parameters['from']);
    	}
    	
    	if (isset($parameters['to']))
    	{
    		$qb->andWhere('r.created < :to')
    		   ->setParameter('to', $parameters['to']);
    	}
    	
    	if (isset($parameters['userid']))
    	{
    		$qb->andWhere("r.userid = :id")
    		   ->setParameter("id", $parameters['userid']);
    	}
    	
    	if (isset($parameters['paid']))
    	{
    		$qb->andWhere("r.paid = :paid")
    		->setParameter("paid", $parameters['paid']);
    	}
    	
    	$total = $qb->getQuery()->getSingleScalarResult();
    	
    	return $total;
    }
    
    public static function setRepairsPaid($em, $parameters)
    {
    	foreach ($parameters->ids as $repairid){
    		$repair = $em->getRepository("RenovateMainBundle:Repair")->find($repairid);
    		if ($repair != null){
    			$repair->setPaid($parameters->paid);
    			$em->persist($repair);
    		}
    	}
    	$em->flush();
    	return true;
    }
    
    public static function addRepair($em, $parameters)
    {
    	$user = $em->getRepository("RenovateMainBundle:User")->find($parameters->userid);
    	
    	$repair = new Repair();
    	$repair->setDescription($parameters->description);
    	$repair->setUserid($user->getId());
    	$repair->setUser($user);
    	$repair->setCreated(new \DateTime());
    	$repair->setPaid(false);
    	$repair->setPrice($parameters->price);
    	
    	$em->persist($repair);
    	$em->flush();
    	
    	return $repair;
    }
    
    public static function removeRepairById($em, $id)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('RenovateMainBundle:Repair', 'r')
    	->where('r.id = :id')
    	->setParameter('id', $id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public static function editRepairById($em, $id, $parameters)
    {
    	$repair = $em->getRepository("RenovateMainBundle:Repair")->find($id);
    	$user = $em->getRepository("RenovateMainBundle:User")->find($parameters->userid);
    	
    	$repair->setUserid($user->getId());
    	$repair->setUser($user);
    	$repair->setDescription($parameters->description);
    	$repair->setPrice($parameters->price);
    	
    	$em->persist($repair);
    	$em->flush();
    	
    	return $repair;
    }
}
