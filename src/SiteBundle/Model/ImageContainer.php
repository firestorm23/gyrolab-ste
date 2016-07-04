<?php

namespace SiteBundle\Model;

use SiteBundle\Entity\File;

interface ImageContainer {


    /**
     * @return integer
     */
    public function getId();

    /**
     * Set file
     *
     * @return File
     */

    public function getImage();

    public function setImage($image);

}

?>