<?php

namespace SiteBundle\Services;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SiteBundle\Entity\Article;
use Symfony\Component\Routing\Router;

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