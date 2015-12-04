<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Portfolio
 *
 * @ORM\Table(name="portfolio")
 * @ORM\Entity
 */
class Portfolio
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
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showOnHomePage", type="boolean")
     */
    private $showOnHomePage;

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
     * Set image
     *
     * @param string $image
     * @return Portfolio
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set showOnHomePage
     *
     * @param boolean $showOnHomePage
     * @return Portfolio
     */
    public function setShowOnHomePage($showOnHomePage)
    {
        $this->showOnHomePage = $showOnHomePage;

        return $this;
    }

    /**
     * Get showOnHomePage
     *
     * @return boolean 
     */
    public function getShowOnHomePage()
    {
        return $this->showOnHomePage;
    }
}
