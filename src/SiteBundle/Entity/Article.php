<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\ArticleRepository")
 */
class Article
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=4096)
     */
    private $body;

    /**
     *
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *
     */

    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="top_banner_sort", type="integer", nullable=true)
     */
    private $topBannerSort;

    /**
     * @var integer
     *
     * @ORM\Column(name="recommended_sort", type="integer", nullable=true)
     */
    private $recommendedSort;

    /**
     * @var integer
     *
     * @ORM\Column(name="top_sort", type="integer", nullable=true)
     */
    private $topSort;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    private $sort;

    /**
     * @Gedmo\Slug(fields={"translit_name"}, updatable=true)
     * @ORM\Column(name="slug", length=128, unique=true)
     * @Assert\Regex("#[a-z_]*#")
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="translit_name", type="string", length=128)
     */
    private $translit_name;
    /**
     * @var integer
     *
     * @ORM\Column(name="allowComments", type="integer", nullable=true)
     */
    private $allowComments;
    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="article")
     */
    private $replies;
    /**
     * @var integer
     *
     * @ORM\Column(name="viewCount", type="integer", nullable=true)
     */
    private $viewCount;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAdded", type="date", nullable=true)
     */

    private $dateAdded;
    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="tags")
     * @ORM\JoinTable(name="article_tag")
     */
    private $tags;
    /**
     * @ORM\ManyToOne(targetEntity="Tag")
     * @ORM\JoinColumn(name="main_tag_id", referencedColumnName="id")
     */

    private $mainTag;

    function __construct()
    {
        $this->dateAdded = new \DateTime();
        $this->tags = new ArrayCollection();
        $this->allowComments = true;
    }

    /**
     * @return string
     */
    public function getTranslitName()
    {
        return $this->translit_name;
    }

    /**
     * @param string $translit_name
     */
    public function setTranslitName($translit_name)
    {
        $this->translit_name = $translit_name;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * @param mixed $replies
     */
    public function setReplies($replies)
    {
        $this->replies = $replies;
    }

    /**
     * @return int
     */
    public function isAllowComments()
    {
        return $this->allowComments;
    }

    /**
     * @param int $allowComments
     */
    public function setAllowComments($allowComments)
    {
        $this->allowComments = $allowComments;
    }

    public function addReplies($reply)
    {
        $this->replies[] = $reply;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return int
     */
    public function getTopSort()
    {
        return $this->topSort;
    }

    /**
     * @param int $topSort
     */
    public function setTopSort($topSort)
    {
        $this->topSort = $topSort;
    }

    /**
     * @return mixed
     */
    public function getMainTag()
    {
        return $this->mainTag;
    }

    /**
     * @param mixed $mainTag
     */
    public function setMainTag($mainTag)
    {
        $this->mainTag = $mainTag;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tag
     */

    public function addTags(Tag $tag)
    {
        $this->tags[] = $tag;
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
     * @return Article
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->translit_name = rus2translit($name);

        return $this;
    }

    /**
     * @return int
     */
    public function getTopBannerSort()
    {
        return $this->topBannerSort;
    }

    /**
     * @param int $topBannerSort
     */
    public function setTopBannerSort($topBannerSort)
    {
        $this->topBannerSort = $topBannerSort;
    }

    /**
     * @return int
     */
    public function getRecommendedSort()
    {
        return $this->recommendedSort;
    }

    /**
     * @param int $recommendedSort
     */
    public function setRecommendedSort($recommendedSort)
    {
        $this->recommendedSort = $recommendedSort;
    }

    /**
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * @param int $viewCount
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;
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
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get image
     *
     * @return File
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param File $image
     *
     * @return Article
     */
    public function setImage($image = null)
    {

        $this->image = $image;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
