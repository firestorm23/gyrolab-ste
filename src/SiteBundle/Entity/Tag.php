<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\TagRepository")
 */
class Tag
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $articles;


    /**
     * @var string
     *
     * @ORM\Column(name="tag_of_the_day", type="boolean")
     */
    private $tagOfTheDay;

    /**
     * @return string
     */
    public function getTagOfTheDay()
    {
        return $this->tagOfTheDay;
    }

    public function isTagOfTheDay()
    {
        return $this->tagOfTheDay;
    }

    /**
     * @param string $tagOfTheDay
     */
    public function setTagOfTheDay($tagOfTheDay)
    {
        $this->tagOfTheDay = $tagOfTheDay;
    }

    function __construct()
    {
        $this->color = "#ED1C24";
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tag
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
     * Set color
     *
     * @param string $color
     *
     * @return Tag
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

    public function __toString() {
        return $this->name;
    }
}

