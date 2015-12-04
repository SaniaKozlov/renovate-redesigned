<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="projects")
 * @ORM\Entity
 */
class Project
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
	 * @ORM\Column(name="budget", type="integer")
	 */
	private $budget;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="time", type="integer")
	 */
	private $time;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_workers", type="integer")
	 */
	private $percentWorkers;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_brigadier", type="integer")
	 */
	private $percentBrigadier;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="color", type="string", length=255)
	 */
	private $color;

    /**
     * @var boolean
     * @ORM\Column(name="finished", type="boolean")
     */
    private $finished;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="finishedDate", type="datetime")
     */
    private $finishedDate;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="project")
     * @ORM\OrderBy({"start" = "ASC"})
     * @var array
     */
    private $events;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Project
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
     * Set budget
     *
     * @param integer $budget
     * @return Project
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return integer 
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Project
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set percentWorkers
     *
     * @param integer $percentWorkers
     * @return Project
     */
    public function setPercentWorkers($percentWorkers)
    {
        $this->percentWorkers = $percentWorkers;

        return $this;
    }

    /**
     * Get percentWorkers
     *
     * @return integer 
     */
    public function getPercentWorkers()
    {
        return $this->percentWorkers;
    }

    /**
     * Set percentBrigadier
     *
     * @param integer $percentBrigadier
     * @return Project
     */
    public function setPercentBrigadier($percentBrigadier)
    {
        $this->percentBrigadier = $percentBrigadier;

        return $this;
    }

    /**
     * Get percentBrigadier
     *
     * @return integer 
     */
    public function getPercentBrigadier()
    {
        return $this->percentBrigadier;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Project
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set finished
     *
     * @param boolean $finished
     * @return Project
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return boolean
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set finishedDate
     *
     * @param \DateTime $finishedDate
     * @return Project
     */
    public function setFinishedDate($finishedDate)
    {
        $this->finishedDate = $finishedDate;

        return $this;
    }

    /**
     * Get finishedDate
     *
     * @return \DateTime 
     */
    public function getFinishedDate()
    {
        return $this->finishedDate;
    }

    /**
     * Add events
     *
     * @param \Renovate\MainBundle\Entity\Event $events
     * @return Project
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

    public function getInArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'budget' => $this->getBudget(),
            'time' => $this->getTime(),
            'percentWorkers' => $this->getPercentWorkers(),
            'percentBrigadier' => $this->getPercentBrigadier(),
            'color' => $this->getColor(),
            'finished' => $this->getFinished(),
            'finishedDate' => ($this->getFinishedDate())?$this->getFinishedDate()->getTimestamp()*1000:null
        );
    }

    public function generateReport(){
        if (!$this->events->count()) return null;

        $budgetWorkers = $this->budget*($this->percentWorkers/100);
        $budgetBrigadier = $this->budget*($this->percentBrigadier/100);

        $duration = array(
            'hours' => array(
                'working' => 0,
                'saturdays' => 0,
                'sundays' => 0,
                'total' => 0,
                'money' => 0
            ),
            'time' => array(
                'working' => 0,
                'saturdays' => 0,
                'sundays' => 0,
                'total' => 0
            )
        );

        $report = array(
            'users' => array(),
            'budgetWorkers' => $budgetWorkers,
            'budgetBrigadier' => $budgetBrigadier
        );

        $event = $this->events->first();
        $prevDayOfWeek = $event->getStart()->format('N');
        $startTime = $event->getStart();
        $startProject = $startTime;
        $endTime = $event->getEnd();

        foreach ($this->events as $event){
            $user = $event->getUser();

            $start = $event->getStart();
            $end = $event->getEnd();
            $dayOfWeek = $start->format('N');

            if ($dayOfWeek == $prevDayOfWeek){
                if ($end > $endTime) $endTime=$end;
            }else{
                $difference = $endTime->diff($startTime);
                $minutes = $difference->h*60+$difference->i;
                switch ($prevDayOfWeek){
                    case '6':
                        $duration['time']['saturdays'] += $minutes;
                        break;
                    case '7':
                        $duration['time']['sundays'] += $minutes;
                        break;
                    default:
                        $duration['time']['working'] += $minutes;
                }
                $prevDayOfWeek = $dayOfWeek;
                $startTime = $start;
                $endTime = $end;
            }

            $difference = $end->diff($start);
            $minutes = $difference->h*60+$difference->i;

            if (!isset($report['users'][$user->getId()])){
                $report['users'][$user->getId()] = array(
                    'user' => $user,
                    'working' => 0,
                    'saturdays' => 0,
                    'sundays' => 0,
                    'total' => 0,
                    'money' => 0
                );
            }

            switch ($dayOfWeek){
                case '6':
                    $duration['hours']['saturdays'] += $minutes;
                    $report['users'][$user->getId()]['saturdays'] += $minutes;
                    break;
                case '7':
                    $duration['hours']['sundays'] += $minutes;
                    $report['users'][$user->getId()]['sundays'] += $minutes;
                    break;
                default:
                    $duration['hours']['working'] += $minutes;
                    $report['users'][$user->getId()]['working'] += $minutes;
            }
        }

        $difference = $endTime->diff($startTime);
        $minutes = $difference->h*60+$difference->i;
        switch ($prevDayOfWeek){
            case '6':
                $duration['time']['saturdays'] += $minutes;
                break;
            case '7':
                $duration['time']['sundays'] += $minutes;
                break;
            default:
                $duration['time']['working'] += $minutes;
        }
        $endProject = $endTime;

        $duration['hours']['saturdays'] /= 60;
        $duration['hours']['sundays'] /= 60;
        $duration['hours']['working'] /= 60;
        $duration['hours']['total'] = $duration['hours']['saturdays']+$duration['hours']['sundays']+$duration['hours']['working'];
        $hourPrice = $budgetWorkers/$duration['hours']['total'];
        $duration['hours']['money'] = $duration['hours']['saturdays']*1.5*$hourPrice+$duration['hours']['sundays']*2*$hourPrice+$duration['hours']['working']*$hourPrice;
        $duration['time']['saturdays'] /= 60;
        $duration['time']['sundays'] /= 60;
        $duration['time']['working'] /= 60;
        $duration['time']['total'] = $duration['time']['saturdays']+$duration['time']['sundays']+$duration['time']['working'];

        $overTime = 0;
        $hours = $duration['time']['total']-$this->time;
        if ($hours>0) $overTime = $hours;
        $report['overTime'] = $overTime;
        $moneyBrigadier = $budgetBrigadier - $overTime*($budgetBrigadier/$this->time);
        if ($moneyBrigadier<0) $moneyBrigadier = 0;
        $report['moneyBrigadier'] = $moneyBrigadier;

        foreach ($report['users'] as $id=>$user){
            $report['users'][$id]['saturdays'] /= 60;
            $report['users'][$id]['sundays'] /= 60;
            $report['users'][$id]['working'] /= 60;
            $report['users'][$id]['total'] = $report['users'][$id]['saturdays']+$report['users'][$id]['sundays']+$report['users'][$id]['working'];
            $report['users'][$id]['money'] = $report['users'][$id]['saturdays']*1.5*$hourPrice+$report['users'][$id]['sundays']*2*$hourPrice+$report['users'][$id]['working']*$hourPrice;
        }

        usort($report['users'],function($a, $b){
            if ($a['money'] == $b['money']) return 0;
            return ($a['money'] > $b['money']) ? -1 : 1;
        });

        $report['hourPrice'] = $hourPrice;
        $report['duration'] = $duration;
        $report['startProject'] = $startProject;
        $report['endProject'] = $endProject;

        return $report;
    }

    public static function getProjects($em, $parameters, $inArray = false)
    {
        $qb = $em->getRepository("RenovateMainBundle:Project")
            ->createQueryBuilder('p');

        $qb->select('p')
            ->addOrderBy('p.id', 'DESC');

        if (isset($parameters['offset']) && isset($parameters['limit']))
        {
            $qb->setFirstResult($parameters['offset'])
                ->setMaxResults($parameters['limit']);
        }

        if (isset($parameters['search']))
        {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('p.name', $qb->expr()->literal('%'.$parameters['search'].'%'))
            ));
        }

        if (isset($parameters['from']))
        {
            $qb->andWhere('p.finishedDate > :from')
                ->setParameter('from', $parameters['from']);
        }

        if (isset($parameters['to']))
        {
            $qb->andWhere('p.finishedDate < :to')
                ->setParameter('to', $parameters['to']);
        }

        if (isset($parameters['finished']))
        {
            $qb->andWhere('p.finished = :finished')
                ->setParameter('finished',$parameters['finished']);
        }

        $result = $qb->getQuery()->getResult();

        if ($inArray)
        {
            return array_map(function($project){
                return $project->getInArray();
            }, $result);
        }else return $result;
    }

    public static function getProjectsCount($em, $parameters)
    {
        $qb = $em->getRepository("RenovateMainBundle:Project")
            ->createQueryBuilder('p');

        $qb->select('COUNT(p.id)');

        if (isset($parameters['search']))
        {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('p.name', $qb->expr()->literal('%'.$parameters['search'].'%'))
            ));
        }

        if (isset($parameters['from']))
        {
            $qb->andWhere('p.finishedDate > :from')
                ->setParameter('from', $parameters['from']);
        }

        if (isset($parameters['to']))
        {
            $qb->andWhere('p.finishedDate < :to')
                ->setParameter('to', $parameters['to']);
        }

        if (isset($parameters['finished']))
        {
            $qb->andWhere('p.finished = :finished')
                ->setParameter('finished',$parameters['finished']);
        }

        $total = $qb->getQuery()->getSingleScalarResult();

        return $total;
    }

    public static function addProject($em, $parameters)
    {
        $project = new Project();
        $project->setName($parameters->name);
        $project->setBudget($parameters->budget);
        $project->setTime($parameters->time);
        $project->setPercentWorkers($parameters->percentWorkers);
        $project->setPercentBrigadier($parameters->percentBrigadier);
        $project->setColor($parameters->color);
        $project->setFinished(false);

        $em->persist($project);
        $em->flush();

        return $project;
    }

    public static function removeProjectById($em, $id)
    {
        $project = $em->getRepository("RenovateMainBundle:Project")->find($id);
        foreach($project->getEvents() as $event){
            $em->remove($event);
        }

        $em->persist($project);
        $em->flush();
        $em->remove($project);
        $em->flush();

        return true;
    }

    public static function editProjectById($em, $id, $parameters)
    {
        $project = $em->getRepository("RenovateMainBundle:Project")->find($id);

        $project->setName($parameters->name);
        $project->setBudget($parameters->budget);
        $project->setTime($parameters->time);
        $project->setPercentWorkers($parameters->percentWorkers);
        $project->setPercentBrigadier($parameters->percentBrigadier);
        $project->setColor($parameters->color);
        $project->setFinished($parameters->finished);

        if ($parameters->finished){
            $events = $em->getRepository("RenovateMainBundle:Event")
                ->createQueryBuilder('e')
                ->select('e')
                ->andWhere('e.projectId = :projectId')
                ->setParameter('projectId',$id)
                ->addOrderBy('e.end', 'DESC')->getQuery()->getResult();
            if(count($events)) $project->setFinishedDate($events[0]->getEnd());
        }else{
            $project->setFinishedDate(null);
        }

        $em->persist($project);
        $em->flush();

        return $project;
    }
}
