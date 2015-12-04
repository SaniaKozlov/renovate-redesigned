<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="services")
 * @ORM\Entity
 */
class Service
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
     * @ORM\Column(name="name", type="string", length=500)
     */
    private $name;

    /**
     * @var boolean
     * @ORM\Column(name="logical", type="boolean")
     */
    private $logical;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
    
    /**
     * @ORM\ManyToOne(targetEntity="ServiceCategory", inversedBy="services")
     * @ORM\JoinColumn(name="categoryid")
     * @var ServiceCategory
     */
    private $category;
    
    /**
     * @ORM\OneToMany(targetEntity="ServicePrice", mappedBy="service")
     * @var array
     */
    private $servicePrices;
    
    /**
     * @ORM\OneToMany(targetEntity="ServiceOption", mappedBy="service")
     * @var array
     */
    private $serviceOptions;
    
    /**
     * @ORM\OneToMany(targetEntity="TariffService", mappedBy="service")
     * @var array
     */
    private $tariffServices;
    
    private function cleanService($em){
    	$qb = $em->createQueryBuilder();
    	 
    	$pricesResult = $qb->delete('RenovateMainBundle:ServicePrice', 'sp')
    	->where('sp.serviceid = :id')
    	->setParameter('id', $this->getId())
    	->getQuery()->getResult();
    	
    	
    	$qb = $em->createQueryBuilder();
    	
    	$optionsResult = $qb->delete('RenovateMainBundle:ServiceOption', 'so')
    	->where('so.serviceid = :id')
    	->setParameter('id', $this->getId())
    	->getQuery()->getResult();
    	 
    	return $pricesResult && $optionsResult;
    }
    
    private function createOptionsAndPrices($em, $parameters){
    	if ($this->getLogical() == true){
    		foreach($parameters->prices as $price){
    			$role = $em->getRepository("RenovateMainBundle:Role")->find($price->roleid);
    
    			$servicePrice = new ServicePrice();
    			$servicePrice->setServiceid($this->getId());
    			$servicePrice->setService($this);
    			$servicePrice->setRoleid($role->getId());
    			$servicePrice->setRole($role);
    			$servicePrice->setValue($price->value);
    			 
    			$em->persist($servicePrice);
    			$em->flush();
    		}
    	}else{
    		foreach($parameters->options as $index => $option){
    			$serviceOption = new ServiceOption();
    			$serviceOption->setServiceid($this->getId());
    			$serviceOption->setService($this);
    			$serviceOption->setName($option->name);
    			 
    			$em->persist($serviceOption);
    			$em->flush();
    
    			foreach($parameters->prices[$index] as $price){
    				$role = $em->getRepository("RenovateMainBundle:Role")->find($price->roleid);
    					
    				$servicePrice = new ServicePrice();
    				$servicePrice->setServiceid($this->getId());
    				$servicePrice->setService($this);
    				$servicePrice->setRoleid($role->getId());
    				$servicePrice->setRole($role);
    				$servicePrice->setOptionid($serviceOption->getId());
    				$servicePrice->setOption($serviceOption);
    				$servicePrice->setValue($price->value);
    					
    				$em->persist($servicePrice);
    				$em->flush();
    			}
    		}
    	}
    	 
    	return true;
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
     * Set categoryid
     *
     * @param integer $categoryid
     * @return Service
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
     * @return Service
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
     * Set logical
     *
     * @param boolean $logical
     * @return Service
     */
    public function setLogical($logical)
    {
    	$this->logical = $logical;
    
    	return $this;
    }
    
    /**
     * Get logical
     *
     * @return boolean
     */
    public function getLogical()
    {
    	return $this->logical;
    }
    
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Service
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
     * @param \Renovate\MainBundle\Entity\ServiceCategory $category
     * @return Service
     */
    public function setCategory(\Renovate\MainBundle\Entity\ServiceCategory $category = null)
    {
    	$this->category = $category;
    
    	return $this;
    }
    
    /**
     * Get category
     *
     * @return \Renovate\MainBundle\Entity\ServiceCategory
     */
    public function getCategory()
    {
    	return $this->category;
    }
    
    /**
     * Add servicePrices
     *
     * @param \Renovate\MainBundle\Entity\ServicePrice $servicePrices
     * @return Service
     */
    public function addServicePrice(\Renovate\MainBundle\Entity\ServicePrice $servicePrices)
    {
    	$this->servicePrices[] = $servicePrices;
    
    	return $this;
    }
    
    /**
     * Remove servicePrices
     *
     * @param \Renovate\MainBundle\Entity\ServicePrice $servicePrices
     */
    public function removeServicePrice(\Renovate\MainBundle\Entity\ServicePrice $servicePrices)
    {
    	$this->servicePrices->removeElement($servicePrices);
    }
    
    /**
     * Get servicePrices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServicePrices()
    {
    	return $this->servicePrices;
    }
    
    /**
     * Add serviceOptions
     *
     * @param \Renovate\MainBundle\Entity\ServiceOption $serviceOptions
     * @return Service
     */
    public function addServiceOption(\Renovate\MainBundle\Entity\ServiceOption $serviceOptions)
    {
    	$this->serviceOptions[] = $serviceOptions;
    
    	return $this;
    }
    
    /**
     * Remove serviceOptions
     *
     * @param \Renovate\MainBundle\Entity\ServiceOption $serviceOptions
     */
    public function removeServiceOption(\Renovate\MainBundle\Entity\ServiceOption $serviceOptions)
    {
    	$this->serviceOptions->removeElement($serviceOptions);
    }
    
    /**
     * Get serviceOptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceOptions()
    {
    	return $this->serviceOptions;
    }
    
    /**
     * Add tariffServices
     *
     * @param \Renovate\MainBundle\Entity\TariffService $tariffServices
     * @return Service
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
     * Constructor
     */
    public function __construct()
    {
    	$this->servicePrices = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'categoryid' => $this->getCategoryid(),
    			'name' => $this->getName(),
    			'logical' => $this->getLogical(),
    			'created' => $this->getCreated()->getTimestamp()*1000,
    			'category' => $this->getCategory()->getInArray(),
    			'prices' => ($this->getServicePrices() == null ) ? array() : array_map(function($price){return $price->getInArray();}, $this->getServicePrices()->toArray()),
    			'options' => ($this->getServiceOptions() == null) ? array() : array_map(function($option){return $option->getInArray();}, $this->getServiceOptions()->toArray())
    	);
    }
    
    public function getPriceForRole($em, $role, $optionid = null){
    	$qb = $em->getRepository("RenovateMainBundle:ServicePrice")
    	->createQueryBuilder('s');
    	
    	$qb->select('s')
    	->andWhere('s.serviceid = :serviceid')
    	->andWhere('s.roleid = :roleid')
    	->setParameter('serviceid', $this->getId())
    	->setParameter('roleid', $role->getId());
    	
    	if ($optionid != null){
    		$qb->andWhere('s.optionid = :optionid')
    		->setParameter('optionid', $optionid);
    	}
    	
    	$result = $qb->getQuery()->getResult();
    	return ($result != null ) ? $result[0]->getValue() : 0 ;
    }
    
    public static function getAllServices($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Service")
    	->createQueryBuilder('s');
    	 
    	$qb->select('s')
    	->orderBy('s.created', 'DESC');
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($service){
    			return $service->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getServices($em, $parameters, $inArray = false)
    {	
    	$qb = $em->getRepository("RenovateMainBundle:Service")
    	->createQueryBuilder('s');
    
    	$qb->select('s')
    	->orderBy('s.created', 'DESC');
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	
    	if (isset($parameters['category']))
    	{
    		$qb->andWhere("s.categoryid = :categoryid")
    		->setParameter("categoryid", $parameters['category']);
    	}
    	
    	if (isset($parameters['logical']))
    	{
    		$qb->andWhere("s.logical = :logical")
    		->setParameter("logical", $parameters['logical']);
    	}
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($service){
    			return $service->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getServicesCalculator($em)
    {
    	$result = array();
    	$categories = ServiceCategory::getAllServiceCategories($em);
    	foreach($categories as $category){
    		$qb = $em->getRepository("RenovateMainBundle:Service")
    		->createQueryBuilder('s')
    		->select('s')
    		->orderBy('s.created', 'DESC')
    		->where("s.categoryid = :id")
    		->setParameter("id", $category->getId());
    		
    		$services = $qb->getQuery()->getResult();
    		
    		$result[] = array('category' => $category->getName(), 'services' => array_map(function($service){return $service->getInArray();}, $services));
    	}
    	return $result;
    }
    
    public static function getServicesCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Service")
    	->createQueryBuilder('s')
    	->select('COUNT(s.id)')
    	->getQuery();
    	 
    	$total = $query->getSingleScalarResult();
    	 
    	return $total;
    }
    
    public static function addService($em, $parameters)
    {
    	$em->getConnection()->beginTransaction();
    	try {
	    	$category = $em->getRepository("RenovateMainBundle:ServiceCategory")->find($parameters->categoryid);
	    	 
	    	$service = new Service();
	    	$service->setName($parameters->name);
	    	$service->setCategoryid($parameters->categoryid);
	    	$service->setCategory($category);
	    	$service->setLogical($parameters->logical);
	    	$service->setCreated(new \DateTime());
	    	
	    	$em->persist($service);
	    	$em->flush();
    	
	    	$service->createOptionsAndPrices($em, $parameters);
	    	
    		$em->getConnection()->commit();
    		return $service;
    	} catch(Exception $e) {
    		$em->getConnection()->rollback();
    		throw $e;
		}
    }
    
    
    
    public static function editServiceById($em, $id, $parameters)
    {
    	$em->getConnection()->beginTransaction();
    	try {
    		$service = $em->getRepository("RenovateMainBundle:Service")->find($id);
    		$category = $em->getRepository("RenovateMainBundle:ServiceCategory")->find($parameters->categoryid);
    		 
    		$service->setName($parameters->name);
    		$service->setCategoryid($parameters->categoryid);
    		$service->setCategory($category);
    		$service->setLogical($parameters->logical);
    		 
    		$em->persist($service);
    		$em->flush();
    		 
    		$service->cleanService($em);
    		$service->createOptionsAndPrices($em, $parameters);
    		 
    		$em->getConnection()->commit();
    		return $service;
    	}catch(Exception $e) {
    		$em->getConnection()->rollback();
    		throw $e;
		}
    	
    }
    
    public static function removeServiceById($em, $id)
    {
    	$em->getConnection()->beginTransaction();
    	try {
	    	$service = $em->getRepository("RenovateMainBundle:Service")->find($id);
	    	$service->cleanService($em);
	    	
	    	$qb = $em->createQueryBuilder();
	    
	    	$qb->delete('RenovateMainBundle:Service', 's')
	    	->where('s.id = :id')
	    	->setParameter('id', $id)
	    	->getQuery()->getResult();
	    
	    	$em->getConnection()->commit();
	    	return true;
    	}catch(Exception $e) {
    		$em->getConnection()->rollback();
    		throw $e;
		}
    }
}
