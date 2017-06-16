<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use GameBundle\Entity\Question;

/**
 * Topic
 *
 * @ORM\Table(name="topic")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\TopicRepository")
 */
class Topic
{
    /**
     * @ORM\OneToMany(targetEntity="GameBundle\Entity\Question", mappedBy="topic", cascade={"remove"})
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity="GameBundle\Entity\Subject", inversedBy="topics", cascade={"persist"})
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
     * @ORM\Column(name="nameTopic", type="string", length=255, nullable=true)
     */
    private $nameTopic;


    /**
     * Add question
     *
     * @param \GameBundle\Entity\Question $question
     *
     * @return Topic
     */
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \GameBundle\Entity\Question $question
     */
    public function removeQuestion(Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Topic
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set nameTopic
     *
     * @param string $nameTopic
     *
     * @return Topic
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
