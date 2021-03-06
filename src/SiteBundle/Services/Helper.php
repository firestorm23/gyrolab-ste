<?php

namespace SiteBundle\Services;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SiteBundle\Entity\Article;
use Symfony\Component\Routing\RouterInterface;

class Helper {

    private $service_container;

    public function __construct(ContainerInterface $service_container) {
        $this->service_container = $service_container;
    }

    public function get($name) {
        return $this->service_container->get($name);
    }

    public function getFileRelPath($absolute_path) {
        $webDir = $this->service_container->get('kernel')->getRootDir()."/../web";
        return strtr($absolute_path, array($webDir => ""));
    }

    public function getBlockSortTypes() {
        $params = $this->service_container->getParameter('block.sort.types');
        fileDump($params, true);
    }

    public function getFileExtension($filename) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        return $ext;
    }

    public function getDownloadFilePath($file) {

        /** @var $router RouterInterface*/
        $router = $this->get('router');
        return $router->generate('download', array('id', $file->getId()));
    }

    public function getOriginalName($file, $without_ext = false) {
        $name = $file->getOriginalName();
        if (empty($name)) {
            return false;
        }

        if ($without_ext) {
            $nameExploded = explode(".",$name);

            return $nameExploded[0];
        }

        return $name;

    }

    public function humanFilesize($filename, $decimals = 2) {
        $bytes = filesize($filename);
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = intval(floor((strlen($bytes) - 1) / 3));
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    public function isImage($filename) {
        $params = $this->service_container->getParameter('image.mimetypes');
        $ext = $this->getFileExtension($filename);

        return in_array($ext, $params);
    }

    public function isDocument($filename) {
        $params = $this->service_container->getParameter('doc.mimetypes');
        $ext = $this->getFileExtension($filename);

        return in_array($ext, $params);
    }

//    public function convertArticlesToMenuLinks($articles, $showArticleTag = true) {
//        /** @var $article Article */
//        $menu = array();
//        /** @var $router Router*/
//        $router = $this->get('router');
////        /** @var $imageResize ImageResize*/
////        $imageResize = $this->get('image_resize');
//        foreach ($articles as $article) {
//            $menu[] = array(
//                'name' => $article->getName(),
//                'link' => $router->generate('article', array('slug' => $article->getSlug())),
//                'tag' => $showArticleTag ? $article->getMainTag()->getName() : "",
//                'image' => $article->getImage()->getResize('100x100x2'),
//                'date' => $article->getDateAdded()
//            );
//        }
//
//        return $menu;
//    }


}

?>