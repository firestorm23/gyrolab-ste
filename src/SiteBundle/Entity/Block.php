<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SiteBundle\Model\ImageContainer;

/**
 * Blocks
 *
 * @ORM\Table(name="blocks")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\BlockRepository")
 */
class Block implements ImageContainer
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
     * @ORM\Column(name="name", type="string", length=4096, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=4086, nullable=true)
     */
    private $body;
    /**
     * @var string
     *
     * @ORM\Column(name="extendedBody", type="string", length=4086, nullable=true)
     */
    private $extendedBody;
    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=4086, nullable=true)
     */
    private $link;
    /**
     *
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *
     */

    private $image;
    /**
     * @ORM\ManyToMany(targetEntity="BlockSort",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="block_block_sort",
     *      joinColumns={@ORM\JoinColumn(name="block_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="block_sort_id", referencedColumnName="id", unique=true, onDelete="CASCADE")}
     *      )
     */

    private $blockSort;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAdded", type="date", nullable=true)
     */

    private $dateAdded;

    function __construct()
    {
        $this->dateAdded = new \DateTime();
        $this->blockSort = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getExtendedBody()
    {
        return $this->extendedBody;
    }

    /**
     * @param string $extendedBody
     */
    public function setExtendedBody($extendedBody)
    {
        $this->extendedBody = $extendedBody;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return array
     */
    public function getBlockSort()
    {
        return $this->blockSort;
    }

    /**
     * @param array $blockSort
     */
    public function setBlockSort($blockSort)
    {
        $this->blockSort = $blockSort;
    }

    /**
     * @param array $blockSort
     */
    public function addBlockSort($blockSort)
    {
        $this->blockSort[] = $blockSort;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        if (is_null($this->image)) {
            return new File();
        }
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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
     * @return Blocks
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Blocks
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

}

