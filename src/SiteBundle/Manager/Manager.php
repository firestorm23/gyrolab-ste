<?php

namespace SiteBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Manager {

    /** @var  ContainerInterface */
    protected $service_container;

    protected function get ($name) {
        return $this->service_container->get($name);
    }

    protected function getDoctrine() {
        /** @var Registry $d */
        $d = $this->get('doctrine');
        return $d;

    }

}

?>