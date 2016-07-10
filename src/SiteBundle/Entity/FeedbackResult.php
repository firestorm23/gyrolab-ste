<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mremi\ContactBundle\Entity\Contact as BaseContact;
use Mremi\ContactBundle\Model\ContactInterface;

/**
 * FeedbackResult
 *
 * @ORM\Table(name="feedback_result")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\FeedbackResultRepository")
 */
class FeedbackResult implements ContactInterface
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
     * @ORM\Column(name="name", type="string", length=4096, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=4096, nullable=true)
     */
    private $subject;
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=4096, nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=4096, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    private $createdAt;

    private $captcha;

    function __construct()
    {
        $this->createdAt= new \DateTime();
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Sets the captcha.
     *
     * @param mixed $captcha
     */
    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;
    }

    /**
     * Gets the captcha.
     *
     * @return mixed
     */
    public function getCaptcha()
    {
        return $this->captcha;
    }

    /**
     * Sets the created at.
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Gets the created at.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets the email address.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Gets the email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the first name.
     *
     * @param string $name
     */
    public function setFirstName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->name;
    }

    /**
     * Sets the last name.
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        return false;
    }

    /**
     * Gets the last name.
     *
     * @return string
     */
    public function getLastName()
    {
        return '';
    }

    /**
     * Gets the first name concatenated to the last name.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->name;
    }

    /**
     * Sets the message.
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        return $this->message = $message;
    }

    /**
     * Gets the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the subject.
     *
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Gets the subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     *
     * @throws \InvalidArgumentException
     */
    public function setTitle($title)
    {
        return false;
    }

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return false;
    }

    /**
     * Gets the title value.
     *
     * @return string
     */
    public function getTitleValue()
    {
        return false;
    }

    /**
     * Gets an array of possible titles.
     *
     * @return array
     */
    public static function getTitles()
    {
        return false;
    }

    /**
     * Gets an array of possible title keys.
     *
     * @return array
     */
    public static function getTitleKeys()
    {
        return false;
    }

    public function toArray()
    {
        return array(
            'name' => $this->name,
            'email'     => $this->email,
            'subject'   => $this->subject,
            'message'   => $this->message,
            'createdAt' => $this->createdAt->format('c'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fromArray(array $data)
    {
        foreach ($data as $property => $value) {
            if (!$value) {
                // prevent to call setters which raise an exception if value is not valid (title for instance)
                // data can be null if associated field is removed from the form
                continue;
            }

            $method = sprintf('set%s', ucfirst($property));

            $this->$method('createdAt' === $property ? new \DateTime($value) : $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($data)
    {
        $this->fromArray(unserialize($data));
    }
}

