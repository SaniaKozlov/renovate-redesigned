<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payments")
 * @ORM\Entity
 */
class Payment
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var float
     *
     * @ORM\Column(name="personal_balance", type="float")
     */
    private $personalBalance;

    /**
     * @var float
     *
     * @ORM\Column(name="building_balance", type="float")
     */
    private $buildingBalance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="payments")
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
     * @return Payment
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
     * @return Payment
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
     * Set amount
     *
     * @param float $amount
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set personalBalance
     *
     * @param float $personalBalance
     * @return Payment
     */
    public function setPersonalBalance($personalBalance)
    {
        $this->personalBalance = $personalBalance;

        return $this;
    }

    /**
     * Get personalBalance
     *
     * @return float 
     */
    public function getPersonalBalance()
    {
        return $this->personalBalance;
    }

    /**
     * Set buildingBalance
     *
     * @param float $buildingBalance
     * @return Payment
     */
    public function setBuildingBalance($buildingBalance)
    {
        $this->buildingBalance = $buildingBalance;

        return $this;
    }

    /**
     * Get buildingBalance
     *
     * @return float 
     */
    public function getBuildingBalance()
    {
        return $this->buildingBalance;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Payment
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
     * Set user
     *
     * @param \Renovate\MainBundle\Entity\User $user
     * @return Payment
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
    			'name' => $this->getName(),
    			'amount' => $this->getAmount(),
    			'personalBalance' => $this->getPersonalBalance(),
    			'buildingBalance' => $this->getBuildingBalance(),
    			'created' => $this->getCreated()->getTimestamp()*1000
    	);
    }
    
    public static function getUserPayments($em, $userid, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Payment")
    	->createQueryBuilder('p');
    
    	$qb->select('p')
    	->orderBy('p.created', 'DESC')
    	->where('p.userid = :userid')
    	->setParameter('userid', $userid);
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($payment){
    			return $payment->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getUserPaymentsCount($em, $userid)
    {
    	$query = $em->getRepository("RenovateMainBundle:Payment")
    	->createQueryBuilder('p')
    	->where('p.userid = :userid')
    	->setParameter('userid', $userid)
    	->select('COUNT(p.id)')
    	->getQuery();
    	 
    	$total = $query->getSingleScalarResult();
    	 
    	return $total;
    }
    
    public static function import($em, $document)
    {
    	$result = false;
    	if ($document->isCSV()){
	    	$url = $document->getPath();
	    	
	    	$file = fopen($url, "r");
	    	while (($data = fgetcsv($file, 8000, "	")) !== FALSE) {
	    		$user = $em->getRepository("RenovateMainBundle:User")->findOneByUsername($data[5]);
	    		
	    		if ($user != NULL){
	    			$payment = new Payment();
	    			$payment->setUserid($user->getId());
	    			$payment->setUser($user);
	    			$payment->setName($data[1]);
	    			$payment->setAmount($data[2]);
	    			$payment->setPersonalBalance($data[3]);
	    			$payment->setBuildingBalance($data[4]);
	    			$payment->setCreated(new \DateTime($data[0]));
	    		
	    			$em->persist($payment);
	    			$em->flush();
	    		}
	    	}
	    	
	    	$result = true;
    	}
    	
    	$document->unlink();
    	
    	return $result;
    }
}
