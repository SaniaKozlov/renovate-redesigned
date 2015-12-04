<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tariff
 *
 * @ORM\Table(name="tariffs")
 * @ORM\Entity
 */
class Tariff
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
     * @ORM\Column(name="parentid", type="integer")
     */
    private $parentid;

    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float")
     */
    private $discount;

    /**
     * @var float
     *
     * @ORM\Column(name="payment", type="float")
     */
    private $payment;

    /**
     * @var integer
     *
     * @ORM\Column(name="squaring", type="integer")
     */
    private $squaring;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activated", type="datetime")
     */
    private $activated;
    
    /**
     * @var string
     *
     * @ORM\Column(name="page", type="string", length=255)
     */
    private $page;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tariffs")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
    /**
     * @ORM\OneToMany(targetEntity="TariffService", mappedBy="tariff")
     * @var array
     */
    private $tariffServices;
    
    /**
     * @ORM\OneToMany(targetEntity="TariffPrice", mappedBy="tariff")
     * @var array
     */
    private $tariffPrices;
    
    private function createTariffServices($em, $parameters){
    	foreach($parameters->services as $id => $_service){
    		if ($_service->checked){
    			$tariffService = new TariffService();
    			$tariffService->setTariffid($this->getId());
    			$tariffService->setTariff($this);
    			
    			$service = $em->getRepository("RenovateMainBundle:Service")->find($id);
    			
    			$tariffService->setServiceid($service->getId());
    			$tariffService->setService($service);
    			
    			if (!$service->getLogical()){
    				$option = $em->getRepository("RenovateMainBundle:ServiceOption")->find($_service->optionid);
    				 
    				$tariffService->setOptionid($option->getId());
    				$tariffService->setOption($option);
    			}
    			
    			$em->persist($tariffService);
    			$em->flush();
    		}
    	}
    }
    
    private function createTariffPrices($em, $parameters){
    	 $clientRoles = Role::getClientRoles($em);
    	 foreach($clientRoles as $role){
    	 	$price = $this->calculatePrice($em, $role, $parameters->services);
    	 	
    	 	$tariffPrice = new TariffPrice();
    	 	$tariffPrice->setTariffid($this->getId());
    	 	$tariffPrice->setTariff($this);
    	 	$tariffPrice->setRoleid($role->getId());
    	 	$tariffPrice->setRole($role);
    	 	$tariffPrice->setValue($price);
    	 	
    	 	$em->persist($tariffPrice);
    	 	$em->flush();
    	 }
    }
    
    private function calculatePrice($em, $role, $services){
    	$suma = 0;
    	foreach($services as $id => $_service){
    		if ($_service->checked){
    			$service = $em->getRepository("RenovateMainBundle:Service")->find($id);
    			$price = $service->getPriceForRole($em, $role, $_service->optionid);
    			$suma = $suma + $price;
    		}
    	}
    	
    	return $suma;
    }
    
    private function cleanTariff($em){
    	$qb = $em->createQueryBuilder();
    	
    	$servicesResult = $qb->delete('RenovateMainBundle:TariffService', 'ts')
    	->where('ts.tariffid = :id')
    	->setParameter('id', $this->getId())
    	->getQuery()->getResult();
    	 
    	 
    	$qb = $em->createQueryBuilder();
    	 
    	$pricesResult = $qb->delete('RenovateMainBundle:TariffPrice', 'tp')
    	->where('tp.tariffid = :id')
    	->setParameter('id', $this->getId())
    	->getQuery()->getResult();
    	
    	return $servicesResult && $pricesResult;
    }
    
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
     * Set parentid
     *
     * @param integer $parentid
     * @return Tariff
     */
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;

        return $this;
    }

    /**
     * Get parentid
     *
     * @return integer 
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Tariff
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
     * Set name
     *
     * @param string $name
     * @return Tariff
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
     * Set active
     *
     * @param boolean $active
     * @return Tariff
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set discount
     *
     * @param float $discount
     * @return Tariff
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
     * Set payment
     *
     * @param float $payment
     * @return Tariff
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return float 
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set squaring
     *
     * @param integer $squaring
     * @return Tariff
     */
    public function setSquaring($squaring)
    {
        $this->squaring = $squaring;

        return $this;
    }

    /**
     * Get squaring
     *
     * @return integer 
     */
    public function getSquaring()
    {
        return $this->squaring;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Tariff
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
     * Set activated
     *
     * @param \DateTime $activated
     * @return Tariff
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Get activated
     *
     * @return \DateTime 
     */
    public function getActivated()
    {
        return $this->activated;
    }
    
    /**
     * Set page
     *
     * @param string $page
     * @return Tariff
     */
    public function setPage($page)
    {
    	$this->page = $page;
    
    	return $this;
    }
    
    /**
     * Get page
     *
     * @return string
     */
    public function getPage()
    {
    	return $this->page;
    }
    
    /**
     * Set user
     *
     * @param \Renovate\MainBundle\Entity\User $user
     * @return Tariff
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
    
    /**
     * Add tariffServices
     *
     * @param \Renovate\MainBundle\Entity\TariffService $tariffServices
     * @return Tariff
     */
    public function addTariffService(\Renovate\MainBundle\Entity\TariffService $tariffServices)
    {
    	$this->tariffServices[] = $tariffServices;
    
    	return $this;
    }
    
    /**
     * Remove tariffServices
     *
     * @param \Renovate\MainBundle\Entity\TariffService $tariffServices
     */
    public function removeTariffService(\Renovate\MainBundle\Entity\TariffService $tariffServices)
    {
    	$this->tariffServices->removeElement($tariffServices);
    }
    
    /**
     * Get tariffServices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTariffServices()
    {
    	return $this->tariffServices;
    }
    
    /**
     * Add tariffPrices
     *
     * @param \Renovate\MainBundle\Entity\TariffPrice $tariffPrices
     * @return Tariff
     */
    public function addTariffPrice(\Renovate\MainBundle\Entity\TariffPrice $tariffPrices)
    {
    	$this->tariffPrices[] = $tariffPrices;
    
    	return $this;
    }
    
    /**
     * Remove tariffPrices
     *
     * @param \Renovate\MainBundle\Entity\TariffPrice $tariffPrices
     */
    public function removeTariffPrice(\Renovate\MainBundle\Entity\TariffPrice $tariffPrices)
    {
    	$this->tariffPrices->removeElement($tariffPrices);
    }
    
    /**
     * Get tariffPrices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTariffPrices()
    {
    	return $this->tariffPrices;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->tariffServices = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'parentid' => $this->getParentid(),
    			'userid' => $this->getUserid(),
    			'name' => $this->getName(),
    			'active' => $this->getActive(),
    			'discount' => $this->getDiscount(),
    			'payment' => $this->getPayment(),
    			'squaring' => $this->getSquaring(),
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'activated' => ($this->getActivated() == null ) ? null : $this->getActivated()->getTimestamp()*1000,
    			'page' => $this->getPage(),
    			'user' => ($this->getUser() != null ) ? $this->getUser()->getInArray() : null,
    			'tariffServices' => ($this->getTariffServices() == null ) ? array() : array_map(function($tariffService){return $tariffService->getInArray();}, $this->getTariffServices()->toArray()),
    			'tariffPrices' => ($this->getTariffPrices() == null ) ? array() : array_map(function($tariffPrice){return $tariffPrice->getInArray();}, $this->getTariffPrices()->toArray())
    	);
    }
    
    public static function getTariffs($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Tariff")
    	->createQueryBuilder('t');
    
    	$qb->select('t')
    	->orderBy('t.created', 'DESC');
    	
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	 
    	if (isset($parameters['from']))
    	{
    		$qb->andWhere('t.created > :from')
    		->setParameter('from', $parameters['from']);
    	}
    	
    	if (isset($parameters['to']))
    	{
    		$qb->andWhere('t.created < :to')
    		->setParameter('to', $parameters['to']);
    	}
    	
    	if (isset($parameters['parentid']) && $parameters['parentid'] == 'true'){
    		$qb->andWhere('t.parentid is not NULL');
    	}elseif (isset($parameters['parentid']) && $parameters['parentid'] == 'false'){
    		$qb->andWhere('t.parentid is NULL');
    	}
    	
    	if (isset($parameters['userid'])){
    		$qb->andWhere('t.userid = :userid')
    		->setParameter('userid', $parameters['userid']);
    	}
    	
    	if (isset($parameters['active'])){
    		$qb->andWhere('t.active = :active')
    		->setParameter('active', $parameters['active']);
    	}
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($tariff){
    			return $tariff->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getTariffsCount($em, $parameters)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Tariff")
    	->createQueryBuilder('t');
    	 
    	$qb->select('COUNT(t.id)');
    	 
    	if (isset($parameters['from']))
    	{
    		$qb->andWhere('t.created > :from')
    		->setParameter('from', $parameters['from']);
    	}
    	
    	if (isset($parameters['to']))
    	{
    		$qb->andWhere('t.created < :to')
    		->setParameter('to', $parameters['to']);
    	}
    	
    	if (isset($parameters['parentid']) && $parameters['parentid'] == 'true'){
    		$qb->andWhere('t.parentid is not NULL');
    	}elseif (isset($parameters['parentid']) && $parameters['parentid'] == 'false'){
    		$qb->andWhere('t.parentid is NULL');
    	}
    	
    	if (isset($parameters['userid'])){
    		$qb->andWhere('t.userid = :userid')
    		->setParameter('userid', $parameters['userid']);
    	}
    	
    	if (isset($parameters['active'])){
    		$qb->andWhere('t.active = :active')
    		->setParameter('active', $parameters['active']);
    	}
    	 
    	$total = $qb->getQuery()->getSingleScalarResult();
    	 
    	return $total;
    }
    
    public static function addTariffPublic($em, $parameters)
    {
    	$em->getConnection()->beginTransaction();
    	try {
    		$tariff = new Tariff();
    		$tariff->setName($parameters->name);
    		$tariff->setActive(TRUE);
    		$tariff->setDiscount($parameters->discount);
    		
    		$tariff->setPayment(0);
    		$tariff->setSquaring(1);
    		
    		$tariff->setCreated(new \DateTime());
    		$tariff->setActivated(new \DateTime());
    		$tariff->setPage($parameters->page);
    		
    		$em->persist($tariff);
    		$em->flush();
    		 
    		$tariff->createTariffServices($em, $parameters);
    		$tariff->createTariffPrices($em, $parameters);
    
    		$em->getConnection()->commit();
    		return $tariff;
    	} catch(Exception $e) {
    		$em->getConnection()->rollback();
    		throw $e;
    	}
    }
    
    public static function addTariffPrivate($em, \Renovate\MainBundle\Entity\User $user, $parameters)
    {
    	$em->getConnection()->beginTransaction();
    	try {
    		$tariffParent = $em->getRepository("RenovateMainBundle:Tariff")->find($parameters->id);
    		
    		$tariff = new Tariff();
    		$tariff->setParentid($tariffParent->getId());
    		$tariff->setUserid($user->getId());
    		$tariff->setUser($user);
    		
    		$name = ($parameters->edited == true) ? $tariffParent->getName().'+' : $tariffParent->getName();
    		$tariff->setName($name);
    		
    		$tariff->setActive(FALSE);
    		$tariff->setDiscount($tariffParent->getDiscount());
    		$tariff->setSquaring($parameters->squaring);
    		$tariff->setPayment(0);
    		$tariff->setCreated(new \DateTime());
    		
    		$em->persist($tariff);
    		$em->flush();
    		
    		$role = $em->getRepository("RenovateMainBundle:Role")->find($parameters->clientRole->id);
    		
    		$price = $tariff->calculatePrice($em, $role, $parameters->services);
    		$tariffPrice = new TariffPrice();
    		$tariffPrice->setTariffid($tariff->getId());
    		$tariffPrice->setTariff($tariff);
    		$tariffPrice->setRoleid($role->getId());
    		$tariffPrice->setRole($role);
    		$tariffPrice->setValue($price);
    		 
    		$em->persist($tariffPrice);
    		$em->flush();
    		
    		$tariff->setPayment($price*$tariff->getSquaring());
    		
    		$em->persist($tariff);
    		$em->flush();
    		
    		$tariff->createTariffServices($em, $parameters);
    		
    		$em->getConnection()->commit();
    		return $tariff;
    	}catch(Exception $e) {
    		$em->getConnection()->rollback();
    		throw $e;
    	}
    }
    
    public static function editTariffPublicById($em, $id, $parameters)
    {
    	$em->getConnection()->beginTransaction();
    	try {
    		$tariff = $em->getRepository("RenovateMainBundle:Tariff")->find($id);
    		
    		$tariff->setName($parameters->name);
    		$tariff->setDiscount($parameters->discount);
    		$tariff->setActivated(new \DateTime());
    		$tariff->setPage($parameters->page);
    		
    		$em->persist($tariff);
    		$em->flush();

    		$tariff->cleanTariff($em);
    		
    		$tariff->createTariffServices($em, $parameters);
    		$tariff->createTariffPrices($em, $parameters);
    		
    		$em->getConnection()->commit();
    		return $tariff;
    	}catch(Exception $e) {
    		$em->getConnection()->rollback();
    		throw $e;
    	}
    }
    
    public static function editTariffPrivateById($em, $id, $parameters)
    {
    	$tariff = $em->getRepository("RenovateMainBundle:Tariff")->find($id);
    
    	$tariff->setDiscount($parameters->discount);
    		
    	$em->persist($tariff);
    	$em->flush();
    
    	return $tariff;
    }
    
    public static function removeTariffById($em, $id)
    {
    	$em->getConnection()->beginTransaction();
    	try {
    		$tariff = $em->getRepository("RenovateMainBundle:Tariff")->find($id);
    		$tariff->cleanTariff($em);
    		
    		$em->remove($tariff);
    		$em->flush();
    		
    		$em->getConnection()->commit();
    		return true;
    	}catch(Exception $e) {
    		$em->getConnection()->rollback();
    		throw $e;
    	}
    }
    
    public static function activateTariffPrivateById($em, $id)
    {
    	$em->getConnection()->beginTransaction();
    	try {
    		$newTariff = $em->getRepository("RenovateMainBundle:Tariff")->find($id);
    		
    		
    		$tariffs = Tariff::getTariffs($em, array('parentid' => 'true', 
							    		  'userid' => $newTariff->getUserid(), 
							    		  'active' => '1'));
    		foreach($tariffs as $tariff){
    			$tariff->cleanTariff($em);
    			$em->remove($tariff);
    			$em->flush();
    		}
    		
    		$newTariff->setActive(TRUE);
    		$newTariff->setActivated(new \DateTime());
    
    		$em->persist($newTariff);
    		$em->flush();
    		 
    		$em->getConnection()->commit();
    		return true;
    	}catch(Exception $e) {
    		$em->getConnection()->rollback();
    		throw $e;
    	}
    }
}
