<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\SubjectRepository")
 */
class Subject
{
    /**
     * @ORM\OneToMany(targetEntity="GameBundle\Entity\Question", mappedBy="subject")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="GameBundle\Entity\Topic", mappedBy="subject")
     */
    private $topics;

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
     * @ORM\Column(name="nameSubject", type="string", length=255)
     */
    private $nameSubject;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->topics = new ArrayCollection();
        $this->questions = new ArrayCollection();
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
     * Set nameSubject
     *
     * @param string $nameSubject
     *
     * @return Subject
     */
    public function setNameSubject($nameSubject)
    {
        $this->nameSubject = $nameSubject;

        return $this;
    }

    /**
     * Get nameSubject
     *
     * @return string
     */
    public function getNameSubject()
    {
        return $this->nameSubject;
    }

    /**
     * Add question
     *
     * @param \GameBundle\Entity\Question $question
     *
     * @return Subject
     */
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;

        $question->setSubject($this);

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
     * Add topic
     *
     * @param \GameBundle\Entity\Topic $topic
     *
     * @return Subject
     */
    public function addTopic(Topic $topic)
    {
        $this->topics[] = $topic;
        
        $topic->setSubject($this);
        
        return $this;
    }

    /**
     * Remove topic
     *
     * @param \GameBundle\Entity\Topic $topic
     */
    public function removeTopic(Topic $topic)
    {
        $this->topics->removeElement($topic);
    }

    /**
     * Get topics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTopics()
    {
        return $this->topics;
    }
}
