<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TariffService
 *
 * @ORM\Table(name="tariffs_services")
 * @ORM\Entity
 */
class TariffService
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
     * @ORM\Column(name="tariffid", type="integer")
     */
    private $tariffid;

    /**
     * @var integer
     *
     * @ORM\Column(name="serviceid", type="integer")
     */
    private $serviceid;

    /**
     * @var integer
     *
     * @ORM\Column(name="optionid", type="integer")
     */
    private $optionid;

    /**
     * @ORM\ManyToOne(targetEntity="Tariff", inversedBy="tariffServices")
     * @ORM\JoinColumn(name="tariffid")
     * @var Tariff
     */
    private $tariff;
    
    /**
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="tariffServices")
     * @ORM\JoinColumn(name="serviceid")
     * @var Service
     */
    private $service;
    
    /**
     * @ORM\ManyToOne(targetEntity="ServiceOption", inversedBy="tariffServices")
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
     * Set tariffid
     *
     * @param integer $tariffid
     * @return TariffService
     */
    public function setTariffid($tariffid)
    {
        $this->tariffid = $tariffid;

        return $this;
    }

    /**
     * Get tariffid
     *
     * @return integer 
     */
    public function getTariffid()
    {
        return $this->tariffid;
    }

    /**
     * Set serviceid
     *
     * @param integer $serviceid
     * @return TariffService
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
     * Set optionid
     *
     * @param integer $optionid
     * @return TariffService
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
     * Set tariff
     *
     * @param \Renovate\MainBundle\Entity\Tariff $tariff
     * @return TariffService
     */
    public function setTariff(\Renovate\MainBundle\Entity\Tariff $tariff = null)
    {
        $this->tariff = $tariff;

        return $this;
    }

    /**
     * Get tariff
     *
     * @return \Renovate\MainBundle\Entity\Tariff 
     */
    public function getTariff()
    {
        return $this->tariff;
    }

    /**
     * Set service
     *
     * @param \Renovate\MainBundle\Entity\Service $service
     * @return TariffService
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
     * Set option
     *
     * @param \Renovate\MainBundle\Entity\ServiceOption $option
     * @return TariffService
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
    			'tariffid' => $this->getTariffid(),
    			'serviceid' => $this->getServiceid(),
    			'optionid' => $this->getOptionid()
    	);
    }
}
