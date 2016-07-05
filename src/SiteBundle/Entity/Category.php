<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\CategoryRepository")
 */
class Category
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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true, nullable=false)
     */
    private $slug;
    /**
     * @var sort
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    private $sort;
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_main", type="boolean", nullable=true)
     */
    private $isMain;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1024, unique=true)
     */
    private $description;
    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="categories")
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $products;

    function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return sort
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param sort $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @return boolean
     */
    public function isIsMain()
    {
        return $this->isMain;
    }

    /**
     * @param boolean $isMain
     */
    public function setIsMain($isMain)
    {
        $this->isMain = $isMain;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return mixed
     */
    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }

    public function addProducts($product)
    {
        $this->products[] = $product;
        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}