<?php

namespace SiteBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function getAllMainCategories() {
        $qb = $this->createQueryBuilder('c');
        $qb->where($qb->expr()->isNotNull('c.slug'))
            ->andWhere($qb->expr()->isNotNull('c.isMain'))
            ->orderBy('c.isMain', 'desc');

        return $qb->getQuery()
            ->getResult();
    }

    public function getMainCategories($slug) {
        $qb = $this->createQueryBuilder('c');
        $qb->where($qb->expr()->eq('c.slug', "'".$slug."'"))
            ->andWhere($qb->expr()->isNotNull('c.isMain'))
            ->orderBy('c.isMain', 'desc');

        return $qb->getQuery()
            ->getResult();
    }
}
