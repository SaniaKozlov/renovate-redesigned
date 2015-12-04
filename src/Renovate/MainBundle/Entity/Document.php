<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document
 *
 * @ORM\Table(name="documents")
 * @ORM\Entity
 */
class Document 
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
     * @Assert\NotBlank;
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     * @Assert\NotBlank;
     */
    private $path;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uploaded", type="datetime")
     */
    private $uploaded;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file = null;
    
    /**
     * @ORM\OneToMany(targetEntity="Job", mappedBy="document")
     * @var array
     */
    private $jobs;
    
    /**
     * @ORM\OneToMany(targetEntity="Job", mappedBy="label")
     * @var array
     */
    private $jobsLabels;
    
    /**
     * @ORM\OneToMany(targetEntity="News", mappedBy="document")
     * @var array
     */
    private $news;
    
    /**
     * @ORM\OneToMany(targetEntity="News", mappedBy="label")
     * @var array
     */
    private $newsLabels;
    
    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="document")
     * @var array
     */
    private $results;
    
    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="label")
     * @var array
     */
    private $resultsLabels;
    
    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="document")
     * @var array
     */
    private $articles;
    
    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="label")
     * @var array
     */
    private $articlesLabels;
    
    /**
     * @ORM\OneToMany(targetEntity="Share", mappedBy="document")
     * @var array
     */
    private $shares;
    
    /**
     * @ORM\OneToMany(targetEntity="Share", mappedBy="label")
     * @var array
     */
    private $sharesLabels;
    
    /**
     * @ORM\OneToMany(targetEntity="Vacancy", mappedBy="document")
     * @var array
     */
    private $vacancies;
    
    /**
     * @ORM\OneToMany(targetEntity="Vacancy", mappedBy="label")
     * @var array
     */
    private $vacanciesLabels;
    
    /**
     * @ORM\OneToMany(targetEntity="Partner", mappedBy="document")
     * @var array
     */
    private $partners;
    
    private static $fileTypes = array('CSV','JPG','JPEG','GIF','PNG');
    
    private static $SALT = '767usghbe7h8#@4b32n32)_$)&N_*$)*$^@$JHN_$_$*N$@($)@*NH';
    
    
    
    function __construct()
    {	
    	$this->setUploaded(new \DateTime());
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
     * @return Document
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
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    
    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Set uploaded
     *
     * @param \DateTime $uploaded
     * @return Document
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;
        return $this;
    }
    /**
     * Get uploaded
     *
     * @return \DateTime 
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }
    
    /**
     * Sets file
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null, $timestamp, $token)
    {
    	$verifyToken = md5(self::$SALT . $timestamp);
    	if ($token !== $verifyToken) return false;
    	 
    	$this->file = $file;
    	 
    	$this->path = str_replace(' ', '', $this->getFile()->getClientOriginalName());
    	 
    	$this->name = str_replace(' ', '', $this->getFile()->getClientOriginalName());
    	return true;
    }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
    	return $this->file;
    }
    
    /**
     * Add jobs
     *
     * @param \Renovate\MainBundle\Entity\Job $jobs
     * @return Document
     */
    public function addJob(\Renovate\MainBundle\Entity\Job $jobs)
    {
    	$this->jobs[] = $jobs;
    
    	return $this;
    }
    
    /**
     * Remove jobs
     *
     * @param \Renovate\MainBundle\Entity\Job $jobs
     */
    public function removeJob(\Renovate\MainBundle\Entity\Job $jobs)
    {
    	$this->jobs->removeElement($jobs);
    }
    
    /**
     * Get jobs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJobs()
    {
    	return $this->jobs;
    }
    
    /**
     * Add jobsLabels
     *
     * @param \Renovate\MainBundle\Entity\Job $jobsLabels
     * @return Document
     */
    public function addJobsLabel(\Renovate\MainBundle\Entity\Job $jobsLabels)
    {
    	$this->jobsLabels[] = $jobsLabels;
    
    	return $this;
    }
    
    /**
     * Remove jobsLabels
     *
     * @param \Renovate\MainBundle\Entity\Job $jobsLabels
     */
    public function removeJobsLabel(\Renovate\MainBundle\Entity\Job $jobsLabels)
    {
    	$this->jobsLabels->removeElement($jobsLabels);
    }
    
    /**
     * Get jobsLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJobsLabels()
    {
    	return $this->jobsLabels;
    }
    
    /**
     * Add news
     *
     * @param \Renovate\MainBundle\Entity\News $news
     * @return Document
     */
    public function addNews(\Renovate\MainBundle\Entity\News $news)
    {
    	$this->news[] = $news;
    
    	return $this;
    }
    
    /**
     * Remove news
     *
     * @param \Renovate\MainBundle\Entity\News $news
     */
    public function removeNews(\Renovate\MainBundle\Entity\News $news)
    {
    	$this->news->removeElement($news);
    }
    
    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
    	return $this->news;
    }
    
    /**
     * Add newsLabels
     *
     * @param \Renovate\MainBundle\Entity\News $newsLabels
     * @return Document
     */
    public function addNewsLabel(\Renovate\MainBundle\Entity\News $newsLabels)
    {
    	$this->newsLabels[] = $newsLabels;
    
    	return $this;
    }
    
    /**
     * Remove newsLabels
     *
     * @param \Renovate\MainBundle\Entity\News $newsLabels
     */
    public function removeNewsLabel(\Renovate\MainBundle\Entity\News $newsLabels)
    {
    	$this->newsLabels->removeElement($newsLabels);
    }
    
    /**
     * Get newsLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNewsLabels()
    {
    	return $this->newsLabels;
    }
    
    /**
     * Add results
     *
     * @param \Renovate\MainBundle\Entity\Result $results
     * @return Document
     */
    public function addResult(\Renovate\MainBundle\Entity\Result $results)
    {
    	$this->results[] = $results;
    
    	return $this;
    }
    
    /**
     * Remove results
     *
     * @param \Renovate\MainBundle\Entity\Result $results
     */
    public function removeResult(\Renovate\MainBundle\Entity\Result $results)
    {
    	$this->results->removeElement($results);
    }
    
    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResults()
    {
    	return $this->results;
    }
    
    /**
     * Add resultsLabels
     *
     * @param \Renovate\MainBundle\Entity\Result $resultsLabels
     * @return Document
     */
    public function addResultsLabel(\Renovate\MainBundle\Entity\Result $resultsLabels)
    {
    	$this->resultsLabels[] = $resultsLabels;
    
    	return $this;
    }
    
    /**
     * Remove resultsLabels
     *
     * @param \Renovate\MainBundle\Entity\Result $resultsLabels
     */
    public function removeResultsLabel(\Renovate\MainBundle\Entity\Result $resultsLabels)
    {
    	$this->resultsLabels->removeElement($resultsLabels);
    }
    
    /**
     * Get resultsLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultsLabels()
    {
    	return $this->resultsLabels;
    }
    
    /**
     * Add articles
     *
     * @param \Renovate\MainBundle\Entity\Article $articles
     * @return Document
     */
    public function addArticle(\Renovate\MainBundle\Entity\Article $articles)
    {
    	$this->articles[] = $articles;
    
    	return $this;
    }
    
    /**
     * Remove articles
     *
     * @param \Renovate\MainBundle\Entity\Article $articles
     */
    public function removeArticle(\Renovate\MainBundle\Entity\Article $articles)
    {
    	$this->articles->removeElement($articles);
    }
    
    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
    	return $this->articles;
    }
    
    /**
     * Add articlesLabels
     *
     * @param \Renovate\MainBundle\Entity\Article $articlesLabels
     * @return Document
     */
    public function addArticlesLabel(\Renovate\MainBundle\Entity\Article $articlesLabels)
    {
    	$this->articlesLabels[] = $articlesLabels;
    
    	return $this;
    }
    
    /**
     * Remove articlesLabels
     *
     * @param \Renovate\MainBundle\Entity\Article $articlesLabels
     */
    public function removeArticlesLabel(\Renovate\MainBundle\Entity\Article $articlesLabels)
    {
    	$this->articlesLabels->removeElement($articlesLabels);
    }
    
    /**
     * Get articlesLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticlesLabels()
    {
    	return $this->articlesLabels;
    }
    
    /**
     * Add shares
     *
     * @param \Renovate\MainBundle\Entity\Share $shares
     * @return Document
     */
    public function addShare(\Renovate\MainBundle\Entity\Share $shares)
    {
    	$this->shares[] = $shares;
    
    	return $this;
    }
    
    /**
     * Remove shares
     *
     * @param \Renovate\MainBundle\Entity\Share $shares
     */
    public function removeShare(\Renovate\MainBundle\Entity\Share $shares)
    {
    	$this->shares->removeElement($shares);
    }
    
    /**
     * Get shares
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShares()
    {
    	return $this->shares;
    }
    
    /**
     * Add sharesLabels
     *
     * @param \Renovate\MainBundle\Entity\Share $sharesLabels
     * @return Document
     */
    public function addSharesLabel(\Renovate\MainBundle\Entity\Share $sharesLabels)
    {
    	$this->sharesLabels[] = $sharesLabels;
    
    	return $this;
    }
    
    /**
     * Remove sharesLabels
     *
     * @param \Renovate\MainBundle\Entity\Share $sharesLabels
     */
    public function removeSharesLabel(\Renovate\MainBundle\Entity\Share $sharesLabels)
    {
    	$this->sharesLabels->removeElement($sharesLabels);
    }
    
    /**
     * Get sharesLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSharesLabels()
    {
    	return $this->sharesLabels;
    }
    
    /**
     * Add vacancies
     *
     * @param \Renovate\MainBundle\Entity\Vacancy $vacancies
     * @return Document
     */
    public function addVacancy(\Renovate\MainBundle\Entity\Vacancy $vacancies)
    {
    	$this->vacancies[] = $vacancies;
    
    	return $this;
    }
    
    /**
     * Remove vacancies
     *
     * @param \Renovate\MainBundle\Entity\Vacancy $vacancies
     */
    public function removeVacancy(\Renovate\MainBundle\Entity\Vacancy $vacancies)
    {
    	$this->vacancies->removeElement($vacancies);
    }
    
    /**
     * Get vacancies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacancies()
    {
    	return $this->vacancies;
    }
    
    /**
     * Add vacanciesLabels
     *
     * @param \Renovate\MainBundle\Entity\Vacancy $vacanciesLabels
     * @return Document
     */
    public function addVacanciesLabel(\Renovate\MainBundle\Entity\Vacancy $vacanciesLabels)
    {
    	$this->vacanciesLabels[] = $vacanciesLabels;
    
    	return $this;
    }
    
    /**
     * Remove vacanciesLabels
     *
     * @param \Renovate\MainBundle\Entity\Vacancy $vacanciesLabels
     */
    public function removeVacanciesLabel(\Renovate\MainBundle\Entity\Vacancy $vacanciesLabels)
    {
    	$this->vacanciesLabels->removeElement($vacanciesLabels);
    }
    
    /**
     * Get vacanciesLabels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacanciesLabels()
    {
    	return $this->vacanciesLabels;
    }
    
    /**
     * Add partners
     *
     * @param \Renovate\MainBundle\Entity\Partner $partners
     * @return Document
     */
    public function addPartner(\Renovate\MainBundle\Entity\Partner $partners)
    {
    	$this->partners[] = $partners;
    
    	return $this;
    }
    
    /**
     * Remove partners
     *
     * @param \Renovate\MainBundle\Entity\Partner $partners
     */
    public function removePartner(\Renovate\MainBundle\Entity\Partner $partners)
    {
    	$this->partners->removeElement($partners);
    }
    
    /**
     * Get partners
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPartners()
    {
    	return $this->partners;
    }
    
    public function unlink()
    {
    	return unlink($this->getPath());
    }
    
    protected function getUploadRootDir()
    {
    	return __DIR__.'/../../../../'.$this->getUploadDir();
    }
    
    protected function getUploadDir()
    {
    	return 'web/bundles/renovate/documents';
    }
    
    public function isCSV()
    {
    	return $this->getExtension() === 'csv';
    }
    
    public function getExtension()
    {
    	$pathinfo = pathinfo($this->getName());
    	
    	return $pathinfo['extension'];
    }
    
    public function getAbsolutePath()
    {
    	return null === $this->path
    	? null
    	: $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath()
    {
    	return null === $this->path
    	? null
    	: $this->getUploadDir().'/'.$this->path;
    }
    
    public function upload()
    {
    	if (null === $this->getFile()) return false;
    	 
    	$pathinfo = pathinfo($this->getName());
    	 
    	if (in_array(strtoupper($pathinfo['extension']),self::$fileTypes)) {
    		$this->getFile()->move($this->getUploadRootDir(), $this->path);
    		$this->path = $this->getWebPath();
    		$this->file = null;
    		return true;
    	} else {
    		return false;
    	}
    }
    
    public function getLinksCount()
    {
    	return count($this->getJobs())
    		  +count($this->getJobsLabels())
    		  +count($this->getNews())
    		  +count($this->getNewsLabels())
    		  +count($this->getResults())
    		  +count($this->getResultsLabels())
    		  +count($this->getArticles())
    		  +count($this->getArticlesLabels())
    	      +count($this->getShares())
    		  +count($this->getSharesLabels())
    		  +count($this->getVacancies())
    		  +count($this->getVacanciesLabels())
    		  +count($this->getPartners());
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'name' => $this->getName(),
    			'uploaded' => $this->getUploaded()->getTimestamp()*1000,
    			'url' => $this->getPath(),
    			
    			'links' => array(
    				'count' => $this->getLinksCount(),
    				'items' => array(
    						'jobs' => count($this->getJobs()),
    						'jobsLabels' => count($this->getJobsLabels()),
    						'news' => count($this->getNews()),
    						'newsLabels' => count($this->getNewsLabels()),
    						'results' => count($this->getResults()),
    						'resultsLabels' => count($this->getResultsLabels()),
    						'articles' => count($this->getArticles()),
    						'articlesLabels' => count($this->getArticlesLabels()),
    						'shares' => count($this->getShares()),
    						'sharesLabels' => count($this->getSharesLabels()),
    						'vacancies' => count($this->getVacancies()),
    						'vacanciesLabels' => count($this->getVacanciesLabels()),
    						'partners' => count($this->getPartners())
    				)
    			)
    	);
    }
    
    public static function getToken($timestamp)
    {
    	return md5(self::$SALT . $timestamp);
    }
    
    public static function getAvailableTypesInString()
    {
    	$result = '';
    	foreach (self::$fileTypes as $type)
    	{
    		$result .= "'*.".$type."' ";
    	}
    	
    	return $result;
    }
    
    public static function getAllDocuments($em, $inArray = false)
    {
    	$qb = $em->createQueryBuilder();
    	
    	$qb->select('d')
    	   ->from('RenovateMainBundle:Document', 'd')
		   ->orderBy('d.uploaded', 'DESC');
		 
    	$result = $qb->getQuery()->getResult();
    	if ($inArray)
    	{
    		return array_map(function($document){
    			return $document->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getDocuments($em, $parameters, $inArray = false)
    {
    	$qb = $em->getRepository("RenovateMainBundle:Document")
    	->createQueryBuilder('d');
    
    	$qb->select('d')
    	->orderBy('d.uploaded', 'DESC');
    	 
    	if (isset($parameters['offset']) && isset($parameters['limit']))
    	{
    		$qb->setFirstResult($parameters['offset'])
    		->setMaxResults($parameters['limit']);
    	}
    
    	$result = $qb->getQuery()->getResult();
    
    	if ($inArray)
    	{
    		return array_map(function($document){
    			return $document->getInArray();
    		}, $result);
    	}else return $result;
    }
    
    public static function getDocumentsCount($em)
    {
    	$query = $em->getRepository("RenovateMainBundle:Document")
    	->createQueryBuilder('d')
    	->select('COUNT(d.id)')
    	->getQuery();
    	 
    	$total = $query->getSingleScalarResult();
    	 
    	return $total;
    }
    
    public static function removeDocumentById($em, $id)
    {
    	$document = $em->getRepository("RenovateMainBundle:Document")->find($id);
    	
    	$document->unlink();
    	
    	$em->remove($document);
    	$em->flush();
    
    	return true;
    }
}
