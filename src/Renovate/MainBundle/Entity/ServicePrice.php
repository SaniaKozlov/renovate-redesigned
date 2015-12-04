<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServicePrice
 *
 * @ORM\Table(name="service_prices")
 * @ORM\Entity
 */
class ServicePrice
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
     * @ORM\Column(name="serviceid", type="integer")
     */
    private $serviceid;

    /**
     * @var integer
     *
     * @ORM\Column(name="roleid", type="integer")
     */
    private $roleid;

    /**
     * @var integer
     *
     * @ORM\Column(name="optionid", type="integer")
     */
    private $optionid;
    
    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="servicePrices")
     * @ORM\JoinColumn(name="serviceid")
     * @var Service
     */
    private $service;
    
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="servicePrices")
     * @ORM\JoinColumn(name="roleid")
     * @var Role
     */
    private $role;
    
    /**
     * @ORM\ManyToOne(targetEntity="ServiceOption", inversedBy="servicePrices")
     * @ORM\JoinColumn(name="optionid")
     * @var ServiceOption
     */
    private $option;
    
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
     * Set serviceid
     *
     * @param integer $serviceid
     * @return ServicePrice
     */
    public function setServiceid($serviceid)
    {
        $this->serviceid = $serviceid;

        return $this;
    }

    /**
     * Get serviceid
     *
     * @return integer 
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }

    /**
     * Set roleid
     *
     * @param integer $roleid
     * @return ServicePrice
     */
    public function setRoleid($roleid)
    {
        $this->roleid = $roleid;

        return $this;
    }

    /**
     * Get roleid
     *
     * @return integer 
     */
    public function getRoleid()
    {
        return $this->roleid;
    }

    /**
     * Set optionid
     *
     * @param integer $optionid
     * @return ServicePrice
     */
    public function setOptionid($optionid)
    {
    	$this->optionid = $optionid;
    
    	return $this;
    }
    
    /**
     * Get optionid
     *
     * @return integer
     */
    public function getOptionid()
    {
    	return $this->optionid;
    }
    
    /**
     * Set value
     *
     * @param float $value
     * @return ServicePrice
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
     * Set service
     *
     * @param \Renovate\MainBundle\Entity\Service $service
     * @return ServicePrice
     */
    public function setService(\Renovate\MainBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Renovate\MainBundle\Entity\Service 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set role
     *
     * @param \Renovate\MainBundle\Entity\Role $role
     * @return ServicePrice
     */
    public function setRole(\Renovate\MainBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Renovate\MainBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set option
     *
     * @param \Renovate\MainBundle\Entity\ServiceOption $option
     * @return ServicePrice
     */
    public function setOption(\Renovate\MainBundle\Entity\ServiceOption $option = null)
    {
        $this->option = $option;

        return $this;
    }

    /**
     * Get option
     *
     * @return \Renovate\MainBundle\Entity\ServiceOption 
     */
    public function getOption()
    {
        return $this->option;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'serviceid' => $this->getServiceid(),
    			'roleid' => $this->getRoleid(),
    			'optionid' => $this->getOptionid(),
    			'value' => $this->getValue()
    	);
    }
}
