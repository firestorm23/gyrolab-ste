<?php

namespace SiteBundle\Entity;
use Sonata\UserBundle\Entity\BaseGroup;
use Doctrine\ORM\Mapping as ORM;


/**
 * User
 *
 * @ORM\Table(name="user_group")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\UserGroupRepository")
 */

class UserGroup extends BaseGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}
