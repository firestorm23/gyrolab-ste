<?php

namespace SiteBundle\Manager;

use SiteBundle\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SiteBundle\Services\ImageResize;

class FileManager extends Manager {



    private $em;
    /** @var ImageResize */
    private $resize;
    private $kernel;


    public function __construct (ContainerInterface $service_container) {
        $this->service_container = $service_container;
        $this->em = $this->getDoctrine()->getManager();
        $this->resize = $this->get('image_resize');
        $this->kernel = $this->get('kernel');
    }

    public function resizeImage(File $file, $sizekey, $saveFile = true) {
        $size = explode("x", $sizekey);
        $filePath = $file->getDirname()."/".$file->getName();
        $resizes = $file->getResizes();

        if (isset($resizes[$sizekey]) && $this->isFileReadble($resizes[$sizekey])) {
            return $resizes[$sizekey];
        }

        if (count($size) < 2 || intval($size[0]) < 0 || intval($size[1]) < 0)
        {
            return false;
        } else {
            $width = $size[0];
            $height = $size[1];
            $type = $size[2];
            if (empty($type)) {
                $type = $this->resize->constant('CROP_IMAGE_CENTER');
            }
        }

        if ($this->isFileReadble($filePath)) {
            $webDirPath = $this->kernel->getRootDir(). '/../web';
            $resizedDirPath = $webDirPath."/upload/resized";
            $id = md5(serialize(array($filePath, $width, $height, $type)));
            $filePath = $this->resize->checkFileSystem($filePath, $webDirPath, $resizedDirPath);
            if ($filePath) {
                $resizedFilePath = $this->resize->cropAndResize($filePath, $width, $height, $resizedDirPath, $id, $type);
                $file->addResizes($sizekey, $resizedFilePath);
                if ($saveFile) $this->saveImageEntity($file);
                return $resizedFilePath;
            }
        }

        return false;
    }

    public function saveImageEntity(File $file) {

        $this->em->persist($file);
        $this->em->flush();

    }

    public function isFileReadble($filePath) {
        return is_file($filePath) && is_readable($filePath);
    }



}

?>