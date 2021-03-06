<?php

namespace SiteBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    public function getArticles($query = false) {
        $qb = $this->createQueryBuilder('u');
        $now = new \DateTime();
        $qb->where($qb->expr()->isNotNull('u.active'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->andX(
                    $qb->expr()->gte('u.dateShowStart',$now->format('Y-m-d')),
                    $qb->expr()->isNull('u.dateShowEnd')
                ),
                $qb->expr()->andX(
                    $qb->expr()->gte('u.dateShowStart',$now->format('Y-m-d')),
                    $qb->expr()->lte('u.dateShowEnd',$now->format('Y-m-d'))
                )
            ))
            ->orderBy('u.dateShowStart', 'desc');

        if ($query) {
            $result = $qb->getQuery();
        } else {
            $result = $qb->getQuery()->getResult();
        }

        return $result;
    }
}
