<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Join;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Renovate\MainBundle\Entity\UserRepository")
 */
class User implements UserInterface,\Serializable
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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="patronymic", type="string", length=255)
     */
    private $patronymic;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mobilephone", type="string", length=255)
     */
    private $mobilephone;
    
    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_unit", type="string", length=255)
     */
    private $adminUnit;
    
    /**
     * @var string
     *
     * @ORM\Column(name="owner", type="string", length=255)
     */
    private $owner;
    
    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="text")
     */
    private $comments;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered", type="datetime")
     */
    private $registered;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @var array
     */
	private $roles;
	
	/**
	 * @ORM\OneToMany(targetEntity="Tariff", mappedBy="user")
	 * @var array
	 */
	private $tariffs;
	
	/**
	 * @ORM\OneToMany(targetEntity="Repair", mappedBy="user")
	 * @var array
	 */
	private $repairs;
	
	/**
	 * @ORM\OneToMany(targetEntity="Task", mappedBy="user")
	 * @var array
	 */
	private $tasks;
	
	/**
	 * @ORM\OneToMany(targetEntity="Payment", mappedBy="user")
	 * @ORM\OrderBy({"created" = "DESC"})
	 * @var array
	 */
	private $payments;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="user")
     * @var array
     */
    private $events;
    
	public function __construct()
	{
		$this->roles = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set patronymic
     *
     * @param string $patronymic
     * @return User
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * Get patronymic
     *
     * @return string 
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set mobilephone
     *
     * @param string $mobilephone
     * @return User
     */
    public function setMobilephone($mobilephone)
    {
        $this->mobilephone = $mobilephone;

        return $this;
    }

    /**
     * Get mobilephone
     *
     * @return string 
     */
    public function getMobilephone()
    {
        return $this->mobilephone;
    }
    
    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
    	$this->address = $address;
    
    	return $this;
    }
    
    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
    	return $this->address;
    }

    /**
     * Set adminUnit
     *
     * @param string $adminUnit
     * @return User
     */
    public function setAdminUnit($adminUnit)
    {
    	$this->adminUnit = $adminUnit;
    
    	return $this;
    }
    
    /**
     * Get adminUnit
     *
     * @return string
     */
    public function getAdminUnit()
    {
    	return $this->adminUnit;
    }
    
    /**
     * Set owner
     *
     * @param string $owner
     * @return User
     */
    public function setOwner($owner)
    {
    	$this->owner = $owner;
    
    	return $this;
    }
    
    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
    	return $this->owner;
    }
    
    /**
     * Set comments
     *
     * @param string $comments
     * @return User
     */
    public function setComments($comments)
    {
    	$this->comments = $comments;
    
    	return $this;
    }
    
    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
    	return $this->comments;
    }
    
    /**
     * Set registered
     *
     * @param \DateTime $registered
     * @return User
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;

        return $this;
    }

    /**
     * Get registered
     *
     * @return \DateTime 
     */
    public function getRegistered()
    {
        return $this->registered;
    }
    
    public function getSalt()
    {
    	return 'jhs%)dif67364b(-=wdfisy@bm3$4$$j&^*&*&%CJH!!@$KJHG';
    }
    
    public function getRoles()
    {
    	return $this->roles->toArray();
    }
    
    public function eraseCredentials()
    {
    	
    }
    
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
    	return serialize(array(
    			$this->id,
    			$this->username,
    			$this->password
    	));
    }
    
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
    	list (
    		$this->id,
    		$this->username,
    		$this->password
    	) = unserialize($serialized);
    }
    

    /**
     * Add roles
     *
     * @param \Renovate\MainBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Renovate\MainBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Renovate\MainBundle\Entity\Role $roles
     */
    public function removeRole(\Renovate\MainBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }
    
    /**
     * Add tariffs
     *
     * @param \Renovate\MainBundle\Entity\Tariff $tariffs
     * @return User
     */
    public function addTariff(\Renovate\MainBundle\Entity\Tariff $tariffs)
    {
    	$this->tariffs[] = $tariffs;
    
    	return $this;
    }
    
    /**
     * Remove tariffs
     *
     * @param \Renovate\MainBundle\Entity\Tariff $tariffs
     */
    public function removeTariff(\Renovate\MainBundle\Entity\Tariff $tariffs)
    {
    	$this->tariffs->removeElement($tariffs);
    }
    
    /**
     * Get tariffs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTariffs()
    {
    	return $this->tariffs;
    }
    
    /**
     * Add repair
     *
     * @param \Renovate\MainBundle\Entity\Repair $repair
     * @return User
     */
    public function addRepair(\Renovate\MainBundle\Entity\Repair $repair)
    {
    	$this->repairs[] = $repair;
    
    	return $this;
    }
    
    /**
     * Remove repair
     *
     * @param \Renovate\MainBundle\Entity\Repair $repair
     */
    public function removeRepair(\Renovate\MainBundle\Entity\Repair $repair)
    {
    	$this->repairs->removeElement($repair);
    }
    
    /**
     * Get repairs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRepairs()
    {
    	return $this->repairs;
    }
    
    /**
     * Add tasks
     *
     * @param \Renovate\MainBundle\Entity\Task $tasks
     * @return User
     */
    public function addTask(\Renovate\MainBundle\Entity\Task $tasks)
    {
    	$this->tasks[] = $tasks;
    
    	return $this;
    }
    
    /**
     * Remove tasks
     *
     * @param \Renovate\MainBundle\Entity\Task $tasks
     */
    public function removeTask(\Renovate\MainBundle\Entity\Task $tasks)
    {
    	$this->tasks->removeElement($tasks);
    }
    
    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
    	return $this->tasks;
    }
    
    /**
     * Add payments
     *
     * @param \Renovate\MainBundle\Entity\Payment $payments
     * @return User
     */
    public function addPayment(\Renovate\MainBundle\Entity\Payment $payments)
    {
    	$this->payments[] = $payments;
    
    	return $this;
    }
    
    /**
     * Remove payments
     *
     * @param \Renovate\MainBundle\Entity\Payment $payments
     */
    public function removePayment(\Renovate\MainBundle\Entity\Payment $payments)
    {
    	$this->payments->removeElement($payments);
    }
    
    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayments()
    {
    	return $this->payments;
    }

    /**
     * Add events
     *
     * @param \Renovate\MainBundle\Entity\Event $events
     * @return User
     */
    public function addEvent(\Renovate\MainBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Renovate\MainBundle\Entity\Event $events
     */
    public function removeEvent(\Renovate\MainBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }
    
    public function getPersonalBalance()
    {
    	return (count($this->getPayments()) > 0) ? $this->getPayments()->first()->getPersonalBalance() : 0;	
    }
    
    public function getBuildingBalance()
    {
    	return (count($this->getPayments()) > 0) ? $this->getPayments()->first()->getBuildingBalance() : 0;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'username' => $this->getUsername(),
    			'name' => $this->getName(),
    			'surname' => $this->getSurname(),
    			'patronymic' => $this->getPatronymic(),
    			'email' => $this->getEmail(),
    			'mobilephone' => $this->getMobilephone(),
    			'address' => $this->getAddress(),
    			'adminUnit' => $this->getAdminUnit(),
    			'owner' => $this->getOwner(),
    			'comments' => $this->getComments(),
    			'registered' => $this->getRegistered()->getTimestamp()*1000,
    			'roles' => array_map(function($role){return $role->getInArray();}, $this->getRoles())
    	);
    }
    
    public function checkUserTariff($em)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Tariff")
    	->createQueryBuilder('t');
    	 
    	$qb->select('t')
    	->andWhere('t.userid = :userid')
    	->andWhere('t.active = :active')
    	->setParameter('userid', $this->getId())
    	->setParameter('active', 0);
    	 
    	return (count($qb->getQuery()->getResult()) > 0) ? true : false;
    }
    
    public static function checkUserUsername($em, $parameters)
    {
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    
    	$qb->select('u')
    	->andWhere('u.username = :username')
    	->setParameter('username', $parameters['username']);
    	
    	if (isset($parameters['id'])){
    		$qb->andWhere('u.id != :id')
    		->setParameter('id', $parameters['id']);
    	}
    
    	return (count($qb->getQuery()->getResult()) > 0) ? true : false;
    }
    
    public static function checkUserEmail($em, $parameters)
    {
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    
    	$qb->select('u')
    	->andWhere('u.email = :email')
    	->setParameter('email', $parameters['email']);
    	 
    	if (isset($parameters['id'])){
    		$qb->andWhere('u.id != :id')
    		->setParameter('id', $parameters['id']);
    	}
    
    	return (count($qb->getQuery()->getResult()) > 0) ? true : false;
    }
    
    public static function getAllUsers($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    	 
    	$qb->select('u')
    	->addOrderBy('u.surname')
    	->addOrderBy('u.name')
    	->addOrderBy('u.patronymic');
    	 
    	$result = $qb->getQuery()->getResult();
    	 
    	if ($inArray)
    	{
    		return array_map(function($user){
    			return $user->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getUsers($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    
    	$qb->select('u')
    	->addOrderBy('u.registered', 'DESC');
    	
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    	
    	if (isset($parameters['search']))
    	{
    		$qb->where($qb->expr()->orX(
    				$qb->expr()->like('u.username', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.name', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.surname', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.patronymic', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.email', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.mobilephone', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.address', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.adminUnit', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.owner', $qb->expr()->literal('%'.$parameters['search'].'%'))
    		));
    	}
    	
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($user){
    			return $user->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getWorkers($em, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    
    	$qb->select('u')
		   ->join("u.roles", "r")
		   ->where("r.role = 'ROLE_WORKER'")
		   ->addOrderBy('u.surname')
		   ->addOrderBy('u.name')
		   ->addOrderBy('u.patronymic');
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($user){
    			return $user->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getClients($em, $inArray = false)
    {
    	$clientRoles = Role::getClientRoles($em);
    	$clientRolesIds = array_map(function($role){return $role->getId();}, $clientRoles); 
    	
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    
    	$qb->select('u')
    	->join("u.roles", "r")
    	->where($qb->expr()->in('r.id', $clientRolesIds))
    	->addOrderBy('u.surname')
    	->addOrderBy('u.name')
    	->addOrderBy('u.patronymic');
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($user){
    			return $user->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getWorkforce($em, $inArray = false)
    {
    	$workforceRoles = Role::getWorkforceRoles($em);
    	$workforceRolesIds = array_map(function($role){return $role->getId();}, $workforceRoles);
    	 
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    
    	$qb->select('u')
    	->join("u.roles", "r")
    	->where($qb->expr()->in('r.id', $workforceRolesIds))
    	->addOrderBy('u.surname')
    	->addOrderBy('u.name')
    	->addOrderBy('u.patronymic');
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($user){
    			return $user->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getUsersCount($em, $parameters)
    {
    	$qb = $em->getRepository("RenovateMainBundle:User")
    	->createQueryBuilder('u');
    	
    	$qb->select('COUNT(u.id)');
    	
    	if (isset($parameters['search']))
    	{
    		$qb->where($qb->expr()->orX(
    				$qb->expr()->like('u.username', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.name', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.surname', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.patronymic', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.email', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.mobilephone', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.address', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.adminUnit', $qb->expr()->literal('%'.$parameters['search'].'%')),
    				$qb->expr()->like('u.owner', $qb->expr()->literal('%'.$parameters['search'].'%'))
    		));
    	}
    	
    	$total = $qb->getQuery()->getSingleScalarResult();
    	 
    	return $total;
    }
    
    public static function addUser($em, $ef, $parameters)
    {
    	$user = new User();
    	$user->setUsername($parameters->username);
    	
    	$encoder = $ef->getEncoder($user);
    	$password = $encoder->encodePassword($parameters->password,$user->getSalt());
    	$user->setPassword($password);
    	
    	$user->setName($parameters->name);
    	$user->setSurname($parameters->surname);
    	$user->setPatronymic($parameters->patronymic);
    	$user->setEmail($parameters->email);
    	$user->setMobilephone($parameters->mobilephone);
    	$user->setAddress($parameters->address);
    	$user->setRegistered(new \DateTime());
    	
    	if (isset($parameters->adminUnit)){
    		$user->setAdminUnit($parameters->adminUnit);
    	}
    	
    	if (isset($parameters->owner)){
    		$user->setOwner($parameters->owner);
    	}
    	
    	if (isset($parameters->comments)){
    		$user->setComments($parameters->comments);
    	}
    	 
    	foreach($parameters->rolesIds as $role_id){
    		$role = $em->getRepository("RenovateMainBundle:Role")->find($role_id);
    		$role->addUser($user);
    		$user->addRole($role);
    		$em->persist($role);
    	}
    	
    	$em->persist($user);
    	$em->flush();
    	 
    	return $user;
    }
    
    public static function removeUserById($em, $id)
    {	
    	$user = $em->getRepository("RenovateMainBundle:User")->find($id);
    	foreach($user->getRoles() as $role){
    		$role->removeUser($user);
    		$user->removeRole($role);
    	}
    	
    	foreach($user->getTariffs() as $tariff){
    		$em->remove($tariff);
    	}
    	
    	foreach($user->getRepairs() as $repair){
    		$em->remove($repair);
    	}
    	
    	foreach($user->getTasks() as $task){
    		$em->remove($task);
    	}
    	
    	foreach($user->getPayments() as $payment){
    		$em->remove($payment);
    	}

        foreach($user->getEvents() as $event){
            $em->remove($event);
        }
    	
    	$em->persist($user);
    	$em->flush();
    	$em->remove($user);
    	$em->flush();
    	
    	return true;
    }
    
    public static function editUserById($em, $ef, $id, $parameters)
    {
    	$user = $em->getRepository("RenovateMainBundle:User")->find($id);
    	 
    	$user->setUsername($parameters->username);
  
    	if (isset($parameters->password)){
    		$encoder = $ef->getEncoder($user);
    		$password = $encoder->encodePassword($parameters->password,$user->getSalt());
    		$user->setPassword($password);
    	}
    	
    	$user->setName($parameters->name);
    	$user->setSurname($parameters->surname);
    	$user->setPatronymic($parameters->patronymic);
    	$user->setEmail($parameters->email);
    	$user->setMobilephone($parameters->mobilephone);
    	
    	$user->setAddress($parameters->address);
    	 
    	if (isset($parameters->adminUnit)){
    		$user->setAdminUnit($parameters->adminUnit);
    	}else{
    		$user->setAdminUnit(NULL);
    	}
    	 
    	if (isset($parameters->owner)){
    		$user->setOwner($parameters->owner);
    	}else{
    		$user->setOwner(NULL);
    	}
    	
    	if (isset($parameters->comments)){
    		$user->setComments($parameters->comments);
    	}else{
    		$user->setComments(NULL);
    	}
    	
    	$em->persist($user);
    	$em->flush();
    	 
    	foreach($user->getRoles() as $role){
    		$role->removeUser($user);
    		$user->removeRole($role);
    	}
    	$em->persist($user);
    	$em->flush();
    	
    	foreach($parameters->rolesIds as $role_id){
    		$role = $em->getRepository("RenovateMainBundle:Role")->find($role_id);
    		$role->addUser($user);
    		$user->addRole($role);
    		$em->persist($role);
    	}
    	$em->persist($user);
    	$em->flush();
    	
    	return $user;
    }
}
