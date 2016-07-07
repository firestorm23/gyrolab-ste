<?php
use Test\TestBundle\DependencyInjection\ImageResize;
use Test\TestBundle\DependencyInjection\MenuMapHelper;

use Symfony\Component\HttpKernel\Kernel;
use Knp\Menu\Twig\Helper;
use SiteBundle\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MischiefCollective\ColorJizz\Formats\Hex;
use MischiefCollective\ColorJizz\Formats\RGB;


class HelperExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public $container;
    public $image_resize;

    public $tstart;
    public $tend;


    const  DEFAULT_USER_PIC = "/static/assets/img/various/nophoto.png";


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->image_resize = $this->container->get('image_resize');
    }

    public function tStart() {
        $this->tstart = getrusage();
    }

    public function tEnd() {
        $this->tend = getrusage();
    }

    public function getScriptTimeMessage() {
        $message = array(
            "This process used " . $this->getrtime($this->tend, $this->tstart, "utime") .
            " ms for its computations\n",
            "It spent " . rutime($this->tend, $this->tstart, "stime") .
            " ms in system calls\n"
        );
        return $message;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('relpath', array($this, 'getFileRelPath')),
            new \Twig_SimpleFilter('up', array($this, 'toUpper')),
        );
    }

    public function arrayToStringPretty($value, $field) {
        $replace_pairs = $field->getOptions()['replace_pairs'];
        if (!is_array($replace_pairs)) {
            $replace_pairs = array();
        }
        $replace_pairs = array_merge($replace_pairs, array("Array" => "", "(" => "", ")" => ""));

        $ret = print_r($value, true);
        $ret = strtr($ret , $replace_pairs);
        return nl2br($ret);
    }

    public function toUpper($string) {
        return mb_strtoupper($string, 'utf-8');
    }

    function getFileRelPath($absolute_path) {
        $webDir = $this->container->get('kernel')->getRootDir()."/../web";
        return strtr($absolute_path, array($webDir => ""));
    }

    public function getrtime($end, $start, $index) {
        return ($end["ru_$index.tv_sec"]*1000 + intval($end["ru_$index.tv_usec"]/1000))
        -  ($start["ru_$index.tv_sec"]*1000 + intval($start["ru_$index.tv_usec"]/1000));
    }

    public function getFunctions()
    {
        return array(
            'fileDump' => new \Twig_Function_Method($this, 'fileDump'),
            'getObjectVars' => new \Twig_Function_Method($this, 'getObjectVars'),
            'getClassMethods' => new \Twig_Function_Method($this, 'getClassMethods'),
            'keys' => new \Twig_Function_Method($this, 'keys'),
            'getFileName' => new \Twig_Function_Method($this, 'getFileName'),
            'tStart' => new \Twig_Function_Method($this, 'tStart'),
            'tEnd' => new \Twig_Function_Method($this, 'tEnd'),
            'getScriptTimeMessage' => new \Twig_Function_Method($this, 'getScriptTimeMessage'),
            'rgba' => new \Twig_Function_Method($this, 'toRGBA'),
            'array_to_string_pretty' => new \Twig_Function_Method($this, 'arrayToStringPretty'),
            'isImage' => new \Twig_Function_Method($this, 'isImage'),
            'isDocument' => new \Twig_Function_Method($this, 'isDocument'),
            'getFileSize' => new \Twig_Function_Method($this , 'getFileSize'),
            'getFileExt' => new \Twig_Function_Method($this , 'getFileExt'),
            'getLazyResize' => new \Twig_Function_Method($this , 'getLazyResize'),
        );
    }

    public function fileDump($var, $append) {
        fileDump($var, $append);
    }

    public function getLazyResize(File $file, $sizekey) {
        $this->container->get('file.manager')->resizeImage($file, $sizekey);
        return $file->getResize($sizekey);
    }

    public function getFileSize($filename) {
        return $this->container->get('helper')->humanFilesize($filename);
    }

    public function getFileExt($filename) {
        return $this->container->get('helper')->getFileExtension($filename);
    }

    public function isImage($filename) {
        return $this->container->get('helper')->isImage($filename);
    }

    public function isDocument($filename) {
        return $this->container->get('helper')->isDocument($filename);
    }


    public function keys($arr) {
        return array_keys($arr);
    }

    public function getFileName($file, $width_resize = false, $height_resize = false, $type = 'center') {

        if (intval($width_resize) > 0 && intval($height_resize) > 0) {
            $resize_constants = array(
                'center' => 1,
                'entropy' => 2,
                'balanced' => 4,
                'face' => 8
            );

            $type = $resize_constants[$type];
            if (empty($type)) {
                $type = 1;
            }

            return $this->image_resize->resizeImage(
                $file->getDirname()."/".$file->getName(),
                $width_resize,
                $height_resize,
                $type, true
            );
        } else {
            if (is_a($file, 'SiteBundle\Entity\File')) {
                $webDir = $this->container->get('kernel')->getRootDir()."/../web";
                $fileWebPath = strtr($file->getDirname(), array($webDir => ""));
                return $fileWebPath."/".$file->getName();
            }
        }


        if (is_a($file, 'SiteBundle\Entity\File')) {
            $webDir = $this->container->get('kernel')->getRootDir()."/../web";
            $fileWebPath = strtr($file->getDirname(), array($webDir => ""));
            return $fileWebPath."/".$file->getName();
        }
    }

    public function getClassMethods($instance) {
        return get_class_methods($instance);
    }

    public function getObjectVars($instance) {
        $class = get_class($instance);
        $vars = get_object_vars($instance);
        $result = array();
        foreach ($vars as $v) {
            if (!is_a($v, $class)) {
                $result[] = $v;
            }
        }
        return $result;
    }

    public function getName() {
        return 'helper_extension';
    }

    public function toRGBA($hex, $alpha = 1) {
        /** @var $hexColor Hex*/
        $hexColor = Hex::fromString($hex);
        /** @var $rgbColor RGB*/
        $rgbColor = $hexColor->toRGB();
        return "rgba(".$rgbColor->getRed().", ".$rgbColor->getGreen().", ".$rgbColor->getBlue().", ".floatval($alpha).")";
    }
}
?>