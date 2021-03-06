<?php

namespace SiteBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use SiteBundle\Entity\Article;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SiteBundle\Services\ImageResize;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleManager extends Manager {



    private $em;


    public function __construct (ContainerInterface $service_container) {
        $this->service_container = $service_container;
    }


    public function articlesImageResize($articles, $resize_identifiers, &$resized_articles) {
        /** @var $file_manager FileManager*/
        $file_manager = $this->get('file.manager');
        $file_manager->entitiesImageResize($articles, $resize_identifiers, $resized_articles);
    }

    public function singleArticleResizeImage(Article $article, $identifier, &$resized_articles) {
        /** @var $file_manager FileManager*/
        $file_manager = $this->get('file.manager');
        $file_manager->singleEntityResizeImage($article, $identifier, $resized_articles);
    }

    public function getArticleResponse($article, $http_response) {
        if (is_object($article)) return $http_response;
        else throw new NotFoundHttpException('Article not found');
    }


}

?>