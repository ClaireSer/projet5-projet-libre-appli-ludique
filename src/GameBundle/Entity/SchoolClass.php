<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SchoolClass
 *
 * @ORM\Table(name="school_class")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\SchoolClassRepository")
 */
class SchoolClass
{
    /**
     * @ORM\OneToMany(targetEntity="GameBundle\Entity\Question", mappedBy="schoolClass")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\Gamer", mappedBy="schoolClass")
     */
    private $gamers;    

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
     * @ORM\Column(name="school_class", type="string", length=255, unique=true)
     */
    private $schoolClass;


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
     * Set schoolClass
     *
     * @param string $schoolClass
     *
     * @return SchoolClass
     */
    public function setSchoolClass($schoolClass)
    {
        $this->schoolClass = $schoolClass;

        return $this;
    }

    /**
     * Get schoolClass
     *
     * @return string
     */
    public function getSchoolClass()
    {
        return $this->schoolClass;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gamers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add question
     *
     * @param \GameBundle\Entity\Question $question
     *
     * @return SchoolClass
     */
    public function addQuestion(\GameBundle\Entity\Question $question)
    {
        $this->questions[] = $question;

        $question->setSchoolClass($this);

        return $this;
    }

    /**
     * Remove question
     *
     * @param \GameBundle\Entity\Question $question
     */
    public function removeQuestion(\GameBundle\Entity\Question $question)
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
     * Add gamer
     *
     * @param \UserBundle\Entity\Gamer $gamer
     *
     * @return SchoolClass
     */
    public function addGamer(\UserBundle\Entity\Gamer $gamer)
    {
        $this->gamers[] = $gamer;
        
        $gamer->setSchoolClass($this);        

        return $this;
    }

    /**
     * Remove gamer
     *
     * @param \UserBundle\Entity\Gamer $gamer
     */
    public function removeGamer(\UserBundle\Entity\Gamer $gamer)
    {
        $this->gamers->removeElement($gamer);
    }

    /**
     * Get gamers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGamers()
    {
        return $this->gamers;
    }
}
