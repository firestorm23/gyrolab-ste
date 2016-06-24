<?php

namespace SiteBundle\Services;

use stojg\crop;
use Symfony\Component\Config\Definition\Exception\Exception;

class ImageResize
{

    const CROP_IMAGE_CENTER = 1;
    const CROP_IMAGE_ENTROPY = 2;
    const CROP_IMAGE_BALANCED = 4;
    const CROP_IMAGE_FACE = 8;


    /** @var MemcachedHelper $memcachedHelper */
    private $memcachedHelper;

    public function __construct(MemcachedHelper $helper) {
        $this->memcachedHelper = $helper;
    }

    public function cropAndResize($file_path, $width, $height, $resized_dir_path, $id, $type) {
        $current_size = getimagesize($file_path);
        if ($current_size[0] <= $width && $current_size[1] <= $height ) {
            $file_result = $file_path;
        } else {
            $ext = pathinfo($file_path, PATHINFO_EXTENSION);
            $resized_file_path = $resized_dir_path."/".$id.".".$ext;
            switch ($type) {
                case self::CROP_IMAGE_CENTER:
                    $resize = new crop\CropCenter($file_path);
                    break;
                case self::CROP_IMAGE_BALANCED:
                    $resize = new crop\CropBalanced($file_path);
                    break;
                case self::CROP_IMAGE_FACE:
                    $resize = new crop\CropFace($file_path);
                    break;

                case self::CROP_IMAGE_ENTROPY:
                default:
                    $resize = new crop\CropEntropy($file_path);
                    break;

            }

            $cropped = $resize->resizeAndCrop($width, $height);
            $cropped->writeImage($resized_file_path);
            $file_result = $resized_file_path;
        }

        return $file_result;
    }

    public function checkFileSystem($file_path, $web_dir_path, $resized_dir_path) {

        if (!is_dir($resized_dir_path)) {
            mkdir($resized_dir_path);
        }

        if (!is_file($file_path)) {
            $file_path = $web_dir_path . $file_path;
            if (!is_file($file_path)) {
                return false;
            }
        }

        return $file_path;
    }

    public function resizeImage($file_path, $width, $height, $type, $return_local_path)
    {
        global $kernel; //костыль, но что поделать

        if (empty($width) || empty($height)) {
            return false;
        }

        $web_dir_path = $kernel->getRootDir(). '/../web';
        $resized_dir_path = $web_dir_path."/upload/resized";

        $file_path = $this->checkFileSystem($file_path, $web_dir_path, $resized_dir_path);

        if (empty($width) || empty($height)) {
            return false;
        }


        $cache_id = md5(serialize(array($file_path, $width, $height, $type)));
        $file_result = $this->memcachedHelper->getCache($cache_id);
        //fileDump(array($file_result), true);
        if (!is_array($file_result) || empty($file_result)) {
            try {

                $file_result = $this->cropAndResize($file_path, $width, $height, $resized_dir_path, $cache_id, $type);

                $this->memcachedHelper->setCache($cache_id, $file_result);


            } catch (\Exception $e) {
                throw new \Exception("Error while cropping image " . $file_path . " in " . __FILE__ . " : " . __LINE__. ". System message: ".$e->getMessage());
            }
        }
        if ($return_local_path) {
            $file_result = str_replace($web_dir_path, "", $file_result);
        }
        //fileDump(array($file_result), true);

        return $file_result;
    }

    public function constant($key){
        $r = new \ReflectionObject($this);
        if($r->hasConstant($key)){ return $r->getConstant($key); }
        return false;
    }

}