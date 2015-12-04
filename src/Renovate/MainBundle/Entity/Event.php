<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="events")
 * @ORM\Entity
 */
class Event
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
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="project_id", type="integer")
     */
    private $projectId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var datetime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var datetime
     *
     * @ORM\Column(name="end", type="datetime")
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="events")
     * @ORM\JoinColumn(name="user_id")
     * @var User
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="events")
     * @ORM\JoinColumn(name="project_id")
     * @var Project
     */
    private $project;

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
     * Set userId
     *
     * @param integer $userId
     * @return Event
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set projectId
     *
     * @param integer $projectId
     * @return Event
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get projectId
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Event
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Event
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set user
     *
     * @param \Renovate\MainBundle\Entity\User $user
     * @return Event
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
     * Set project
     *
     * @param \Renovate\MainBundle\Entity\Project $project
     * @return Event
     */
    public function setProject(\Renovate\MainBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Renovate\MainBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    public function getInArray()
    {
        return array(
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'projectId' => $this->getProjectId(),
            'title' => $this->getTitle(),
            'color' => $this->getProject()->getColor(),
            'editable' => !$this->getProject()->getFinished(),
            'start' => $this->getStart()->format('Y-m-d H:i:s'),
            'end' => $this->getEnd()->format('Y-m-d H:i:s')
        );
    }

    public static function getEvents($em, $parameters, $inArray = false)
    {
        $qb = $em->getRepository("RenovateMainBundle:Event")
            ->createQueryBuilder('e');

        $qb->select('e')
            ->addOrderBy('e.start', 'ASC');

        if (isset($parameters['from']))
        {
            $qb->andWhere('e.start >= :from')
                ->setParameter('from', $parameters['from']);
        }

        if (isset($parameters['to']))
        {
            $qb->andWhere('e.start < :to')
                ->setParameter('to', $parameters['to']);
        }

        $result = $qb->getQuery()->getResult();

        if ($inArray)
        {
            return array_map(function($event){
                return $event->getInArray();
            }, $result);
        }else return $result;
    }

    public static function editEventById($em, $id, $parameters)
    {
        $event = $em->getRepository("RenovateMainBundle:Event")->find($id);
        $user = $em->getRepository("RenovateMainBundle:User")->find($parameters->userId);
        $project = $em->getRepository("RenovateMainBundle:Project")->find($parameters->projectId);

        $event->setUserId($user->getId());
        $event->setUser($user);
        $event->setProjectId($project->getId());
        $event->setProject($project);

        $event->setTitle($user->getSurname().' '.mb_substr($user->getName(),0,1,'UTF-8').'. '.mb_substr($user->getPatronymic(),0,1,'UTF-8').'. | '.$project->getName());
        $start = new \DateTime($parameters->start);
        $end = new \DateTime($parameters->end);
        if ($start>$end) return false;
        $event->setStart($start);
        $event->setEnd($end);

        $em->persist($event);
        $em->flush();

        return $event;
    }

    public static function addEvent($em, $parameters)
    {
        $event = new Event();
        $user = $em->getRepository("RenovateMainBundle:User")->find($parameters->userId);
        $project = $em->getRepository("RenovateMainBundle:Project")->find($parameters->projectId);

        $event->setUserId($user->getId());
        $event->setUser($user);
        $event->setProjectId($project->getId());
        $event->setProject($project);

        $event->setTitle($user->getSurname().' '.mb_substr($user->getName(),0,1,'UTF-8').'. '.mb_substr($user->getPatronymic(),0,1,'UTF-8').'. | '.$project->getName());
        $start = new \DateTime($parameters->start);
        $end = new \DateTime($parameters->end);
        if ($start>$end) return false;
        $event->setStart($start);
        $event->setEnd($end);

        $em->persist($event);
        $em->flush();

        return $event;
    }

    public static function removeEventById($em, $id)
    {
        $event = $em->getRepository("RenovateMainBundle:Event")->find($id);

        $em->remove($event);
        $em->flush();

        return true;
    }
}
