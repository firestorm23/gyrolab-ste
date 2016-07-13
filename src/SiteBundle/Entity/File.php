<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File as HttpFoundationFile;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\FileRepository")
 *
 * @Vich\Uploadable
 */
class File
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var array
     * @Assert\File( maxSize="100M")
     * @Vich\UploadableField(mapping="upload", fileNameProperty="name")
     *
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="originalName", type="string", length=255, nullable=true)
     */
    private $originalName;

    /**
     * @var string
     *
     * @ORM\Column(name="dirname", type="string", length=255, nullable=true)
     */
    private $dirname;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255, nullable=true)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="mimetype", type="string", length=255, nullable=true)
     */
    private $mimetype;

    /**
     * @var array
     *
     * @ORM\Column(name="resizes", type="array", nullable=true)
     */

    private $resizes;

//    /**
//     * @ORM\ManyToOne(targetEntity="Product", inversedBy="galleryImages")
//     * @ORM\JoinColumn(name="gallery_product_id", referencedColumnName="id")
//     */
//
//    private $galleryProduct;

    function __construct()
    {
        $this->resizes = array();
    }

//    /**
//     * @return mixed
//     */
//    public function getGalleryProduct()
//    {
//        return $this->galleryProduct;
//    }
//
//    /**
//     * @param mixed $galleryProduct
//     */
//    public function setGalleryProduct($galleryProduct)
//    {
//        $this->galleryProduct = $galleryProduct;
//        return $this;
//    }

    /**
     * @return array
     */
    public function getResizes()
    {
        return $this->resizes;
    }

    /**
     * @param array $resizes
     */
    public function setResizes($resizes)
    {
        $this->resizes = $resizes;
    }

    public function addResizes($sizekey, $filename)
    {
        $this->resizes[$sizekey] = $filename;
    }

    public function getResize($sizekey) {
        if (isset($this->resizes[$sizekey]))
            return $this->resizes[$sizekey];
        return false;
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
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get file
     *
     * @return array
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set file
     *
     * @param array $file
     * @return File
     */
    public function setFile($file)
    {
        fileDump($file, true);
        if (is_null($file)) {
            return $this;
        } else if (is_a($file, 'Symfony\Component\HttpFoundation\File\File')) {
            //специальная строчка. принудительно устанавливаем поле name, чтобы подхватилось событие preUpdate, так как само
            //поле file не размечено для Doctrine и не управляется ею
            $this->name = $file->getFilename();
            fileDump(get_class_methods($file), true);
            if (method_exists($file, 'getClientOriginalName')) {
                $this->originalName = $file->getClientOriginalName();
            }
            $this->file = $file;
        }



        return $this;
    }

    /**
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set originalName
     *
     * @param string $originalName
     * @return File
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get dirname
     *
     * @return string
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * Set dirname
     *
     * @param string $dirname
     * @return File
     */
    public function setDirname($dirname)
    {
        $this->dirname = $dirname;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return File
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get mimetype
     *
     * @return string
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * Set mimetype
     *
     * @param string $mimetype
     * @return File
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;

        return $this;
    }

    public function __toString() {
        return $this->getDirname()."/".$this->getName();
    }
}
