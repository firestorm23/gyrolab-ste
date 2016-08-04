<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use SiteBundle\Model\ImageContainer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\ArticleRepository")
 */
class Article implements ImageContainer
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
     * @return string
     */
    public function getExtendedName()
    {
        return $this->extendedName;
    }

    /**
     * @param string $extendedName
     */
    public function setExtendedName($extendedName)
    {
        $this->extendedName = $extendedName;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=4096)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="extended_name", type="string", length=4096, nullable=true)
     */
    private $extendedName;

    /**
     * @var string
     *
     * @ORM\Column(name="preview_text", type="text", nullable=true)
     */
    private $previewText;

    /**
     * @return string
     */
    public function getPreviewText()
    {

        if (empty($this->previewText)) {
            $text = strip_tags($this->body);
            if ($text > 400) // if you want...
            {
                $maxLength = 400;
                return substr($text, 0, $maxLength);
            }
            return $this->body;
        }

        return $this->previewText;
    }

    /**
     * @param string $previewText
     */
    public function setPreviewText($previewText)
    {
        $this->previewText = $previewText;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     *
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *
     */

    private $image;

//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="top_banner_sort", type="integer", nullable=true)
//     */
//    private $topBannerSort;
//
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="recommended_sort", type="integer", nullable=true)
//     */
//    private $recommendedSort;
//
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="top_sort", type="integer", nullable=true)
//     */
//    private $topSort;

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
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="allowComments", type="integer", nullable=true)
//     */
//    private $allowComments;
//    /**
//     * @ORM\OneToMany(targetEntity="Message", mappedBy="article")
//     */
//    private $replies;
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="viewCount", type="integer", nullable=true)
//     */
//    private $viewCount;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAdded", type="date")
     */

    private $dateAdded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateShowStart", type="date", nullable=true)
     */

    private $dateShowStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateShowEnd", type="date", nullable=true)
     */

    private $dateShowEnd;

        /**
     * @var integer
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\ManyToMany(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="article_documentation",
     *      joinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $documentationFiles;

    function __construct()
    {
        $this->dateAdded = new \DateTime();
        $this->dateShowStart = new \DateTime();
        $this->active = true;
        $this->documentationFiles = new ArrayCollection();
//        $this->allowComments = true;
    }

    /**
     * @return \DateTime
     */
    public function getDateShowStart()
    {
        return $this->dateShowStart;
    }

    /**
     * @param \DateTime $dateShowStart
     */
    public function setDateShowStart($dateShowStart)
    {
        $this->dateShowStart = $dateShowStart;
    }

    /**
     * @return \DateTime
     */
    public function getDateShowEnd()
    {
        return $this->dateShowEnd;
    }

    /**
     * @param \DateTime $dateShowEnd
     */
    public function setDateShowEnd($dateShowEnd)
    {
        $this->dateShowEnd = $dateShowEnd;
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param int $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getDocumentationFiles()
    {
        return $this->documentationFiles;
    }

    /**
     * @param mixed $documentationFiles
     */
    public function setDocumentationFiles($documentationFiles)
    {
        $this->documentationFiles = $documentationFiles;
    }

    public function addDocumentationFiles($documentation)
    {
        $this->documentationFiles[] = $documentation;
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

//    /**
//     * @return mixed
//     */
//    public function getReplies()
//    {
//        return $this->replies;
//    }
//
//    /**
//     * @param mixed $replies
//     */
//    public function setReplies($replies)
//    {
//        $this->replies = $replies;
//    }

//    /**
//     * @return int
//     */
//    public function isAllowComments()
//    {
//        return $this->allowComments;
//    }
//
//    /**
//     * @param int $allowComments
//     */
//    public function setAllowComments($allowComments)
//    {
//        $this->allowComments = $allowComments;
//    }

//    public function addReplies($reply)
//    {
//        $this->replies[] = $reply;
//    }

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
//    public function getTopSort()
//    {
//        return $this->topSort;
//    }
//
//    /**
//     * @param int $topSort
//     */
//    public function setTopSort($topSort)
//    {
//        $this->topSort = $topSort;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getMainTag()
//    {
//        return $this->mainTag;
//    }
//
//    /**
//     * @param mixed $mainTag
//     */
//    public function setMainTag($mainTag)
//    {
//        $this->mainTag = $mainTag;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getTags()
//    {
//        return $this->tags;
//    }
//
//    /**
//     * @param mixed $tag
//     */
//
//    public function addTags(Tag $tag)
//    {
//        $this->tags[] = $tag;
//    }


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

//    /**
//     * @return int
//     */
//    public function getTopBannerSort()
//    {
//        return $this->topBannerSort;
//    }

    /**
     * @param int $topBannerSort
     */
//    public function setTopBannerSort($topBannerSort)
//    {
//        $this->topBannerSort = $topBannerSort;
//    }
//
//    /**
//     * @return int
//     */
//    public function getRecommendedSort()
//    {
//        return $this->recommendedSort;
//    }
//
//    /**
//     * @param int $recommendedSort
//     */
//    public function setRecommendedSort($recommendedSort)
//    {
//        $this->recommendedSort = $recommendedSort;
//    }
//
//    /**
//     * @return int
//     */
//    public function getViewCount()
//    {
//        return $this->viewCount;
//    }
//
//    /**
//     * @param int $viewCount
//     */
//    public function setViewCount($viewCount)
//    {
//        $this->viewCount = $viewCount;
//    }

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
        if (is_null($this->image)) {
            return new File();
        }
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
