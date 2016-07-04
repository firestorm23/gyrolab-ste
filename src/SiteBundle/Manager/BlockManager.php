<?php

namespace SiteBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use SiteBundle\Entity\Block;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SiteBundle\Services\ImageResize;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlockManager extends Manager {



    private $em;


    public function __construct (ContainerInterface $service_container) {
        $this->service_container = $service_container;
    }


    public function blocksImageResize($articles, $resize_identifiers, &$resized_articles) {
        /** @var $file_manager FileManager*/
        $file_manager = $this->get('file.manager');
        $file_manager->entitiesImageResize($articles, $resize_identifiers, $resized_articles);
    }

    public function singleBlockResizeImage(Block $article, $identifier, &$resized_articles) {
        /** @var $file_manager FileManager*/
        $file_manager = $this->get('file.manager');
        $file_manager->singleEntityResizeImage($article, $identifier, $resized_articles);
    }

}