<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contacts
 *
 * @ORM\Table(name="contacts")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\ContactsRepository")
 */
class Contacts
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="htmlValue", type="text",  nullable=true)
     */
    private $htmlValue;

    private $viewValue;

    /**
     * @return mixed
     */
    public function getViewValue()
    {
        if (!empty($this->value)) {
            return $this->value;
        } else {
            return $this->htmlValue;
        }
    }


    /**
     * @return string
     */
    public function getHtmlValue()
    {
        return $this->htmlValue;
    }

    /**
     * @param string $htmlValue
     */
    public function setHtmlValue($htmlValue)
    {
        $this->htmlValue = $htmlValue;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Contacts
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Contacts
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Contacts
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

