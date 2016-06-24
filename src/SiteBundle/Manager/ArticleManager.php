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
        /** @var $article Article*/
        foreach ($articles as $article) {
            foreach ($resize_identifiers as $identifier) {
                $file_manager->resizeImage($article->getImage(), $identifier, false);
            }
            $resized_articles[$article->getId()] = $article;
        }
    }

    public function singleArticleResizeImage(Article $article, $identifier, &$resized_articles) {
        $file_manager = $this->get('file.manager');
        $file_manager->resizeImage($article->getImage(), $identifier, false);
        $resized_articles[$article->getId()] = $article;
    }

    public function getArticleResponse($article, $http_response) {
        if (is_object($article)) return $http_response;
        else throw new NotFoundHttpException('Article not found');
    }


}

?>