<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceCategory
 *
 * @ORM\Table(name="service_categories")
 * @ORM\Entity
 */
class ServiceCategory
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
     * @ORM\OneToMany(targetEntity="Service", mappedBy="category")
     * @var array
     */
    private $services;

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
     * @return ServiceCategory
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
     * @return ServiceCategory
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
     * Add services
     *
     * @param \Renovate\MainBundle\Entity\Service $services
     * @return ServiceCategory
     */
    public function addService(\Renovate\MainBundle\Entity\Service $services)
    {
    	$this->services[] = $services;
    
    	return $this;
    }
    
    /**
     * Remove services
     *
     * @param \Renovate\MainBundle\Entity\Service $services
     */
    public function removeService(\Renovate\MainBundle\Entity\Service $services)
    {
    	$this->services->removeElement($services);
    }
    
    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
    	return $this->services;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->services = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'name' => $this->getName(),
    			'level' => $this->getLevel()
    	);
    }
    
    public static function getAllServiceCategories($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:ServiceCategory")
    	->createQueryBuilder('s');
    
    	$qb->select('s')
    	   ->orderBy('s.level', 'DESC');
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($serviceCategory){
    			return $serviceCategory->getInArray();
    		}, $result);
    	}else return $result;
    }
}
