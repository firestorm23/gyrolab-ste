<?php

namespace SiteBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use SiteBundle\Entity\Block;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SiteBundle\Services\ImageResize;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductManager extends Manager {



    private $em;


    public function __construct (ContainerInterface $service_container) {
        $this->service_container = $service_container;
    }


    public function productImageResize($products, $resize_identifiers, &$resized_articles) {
        /** @var $file_manager FileManager*/
        $file_manager = $this->get('file.manager');
        $file_manager->entitiesImageResize($products, $resize_identifiers, $resized_articles);
    }

    public function singleProductResizeImage(Block $product, $identifier, &$resized_articles) {
        /** @var $file_manager FileManager*/
        $file_manager = $this->get('file.manager');
        $file_manager->singleEntityResizeImage($product, $identifier, $resized_articles);
    }

}