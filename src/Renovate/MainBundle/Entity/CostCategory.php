<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CostCategory
 *
 * @ORM\Table(name="cost_categories")
 * @ORM\Entity
 */
class CostCategory
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Cost", mappedBy="category")
     * @ORM\OrderBy({"name" = "ASC"})
     * @var array
     */
    private $costs;

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
     * Set type
     *
     * @param string $type
     * @return CostCategory
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CostCategory
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
     * Constructor
     */
    public function __construct()
    {
        $this->costs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add costs
     *
     * @param \Renovate\MainBundle\Entity\Cost $costs
     * @return CostCategory
     */
    public function addCost(\Renovate\MainBundle\Entity\Cost $costs)
    {
        $this->costs[] = $costs;

        return $this;
    }

    /**
     * Remove costs
     *
     * @param \Renovate\MainBundle\Entity\Cost $costs
     */
    public function removeCost(\Renovate\MainBundle\Entity\Cost $costs)
    {
        $this->costs->removeElement($costs);
    }

    /**
     * Get costs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCosts()
    {
        return $this->costs;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'type' => $this->getType(),
    			'name' => $this->getName(),
    			'costs' => array_map(function($cost){return $cost->getInArray();}, $this->getCosts()->toArray())
    	);
    }
    
    public static function getCostCategories($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:CostCategory")
    	->createQueryBuilder('c');
    
    	$qb->select('c')
    	->orderBy('c.name');
    	
    	if (isset($parameters['type']))
    	{
    		$qb->where('c.type = :type')
    		->setParameter('type', $parameters['type']);
    	}
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($costCategory){
    			return $costCategory->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function addCostCategory($em, $parameters)
    {
    	$costCategory = new CostCategory();
    	$costCategory->setName($parameters->name);
    	$costCategory->setType($parameters->type);
    
    	$em->persist($costCategory);
    	$em->flush();
    
    	return $costCategory;
    }
    
    public static function removeCostCategoryById($em, $id)
    {
    	$costCategory = $em->getRepository("RenovateMainBundle:CostCategory")->find($id);
    	 
    	foreach($costCategory->getCosts() as $cost){
    		Cost::removeCostById($em, $cost->getId());
    	}
    	 
    	$em->remove($costCategory);
    	$em->flush();
    
    	return true;
    }
    
    public static function editCostCategoryById($em, $id, $parameters)
    {
    	$costCategory = $em->getRepository("RenovateMainBundle:CostCategory")->find($id);
    
    	$costCategory->setName($parameters->name);
    
    	$em->persist($costCategory);
    	$em->flush();
    
    	return $costCategory;
    }
}
