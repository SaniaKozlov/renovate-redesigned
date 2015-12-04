<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TariffPrice
 *
 * @ORM\Table(name="tariffs_prices")
 * @ORM\Entity
 */
class TariffPrice
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
     * @ORM\Column(name="roleid", type="integer")
     */
    private $roleid;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Tariff", inversedBy="tariffPrices")
     * @ORM\JoinColumn(name="tariffid")
     * @var Tariff
     */
    private $tariff;
    
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="tariffPrices")
     * @ORM\JoinColumn(name="roleid")
     * @var Role
     */
    private $role;

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
     * @return TariffPrice
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
     * Set roleid
     *
     * @param integer $roleid
     * @return TariffPrice
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
     * Set value
     *
     * @param float $value
     * @return TariffPrice
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
     * Set tariff
     *
     * @param \Renovate\MainBundle\Entity\Tariff $tariff
     * @return TariffPrice
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
     * Set role
     *
     * @param \Renovate\MainBundle\Entity\Role $role
     * @return TariffPrice
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
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'tariffid' => $this->getTariffid(),
    			'roleid' => $this->getRoleid(),
    			'value' => $this->getValue()
    	);
    }
}
