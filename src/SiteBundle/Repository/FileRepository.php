<?php

namespace SiteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SiteBundle\Entity\File;

/**
 * FileRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FileRepository extends EntityRepository
{
    /*public function getFileResize(File $file, $sizekey) {

        $size = explode("x", $sizekey);
        $filePath = $file->getDirname()."/".$file->getName();

        if (count($size) < 2 || intval($size[0]) < 0 || intval($size[1]) < 0)
        {
            return false;
        }

        if (is_file($filePath) && is_readable($filePath)) {

        }

        return false;
    }*/
}
