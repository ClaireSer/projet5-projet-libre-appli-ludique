<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Topic
 *
 * @ORM\Table(name="topic")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\TopicRepository")
 */
class Topic
{
    /**
     * @ORM\ManyToOne(targetEntity="GameBundle\Entity\Subject", inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subject; 

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
     * @ORM\Column(name="nameTopic", type="string", length=255, nullable=true, unique=true)
     */
    private $nameTopic;


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
     * Set nameTopic
     *
     * @param string $nameTopic
     *
     * @return NameTopic
     */
    public function setNameTopic($nameTopic)
    {
        $this->nameTopic = $nameTopic;

        return $this;
    }

    /**
     * Get nameTopic
     *
     * @return string
     */
    public function getNameTopic()
    {
        return $this->nameTopic;
    }

    /**
     * Set subject
     *
     * @param \GameBundle\Entity\Subject $subject
     *
     * @return Topic
     */
    public function setSubject(Subject $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return \GameBundle\Entity\Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
