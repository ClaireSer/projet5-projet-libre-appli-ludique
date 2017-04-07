<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use UserBundle\Entity\UserCount;
/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\UserCount", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userCount;
    
    /**
     * @ORM\OneToMany(targetEntity="GameBundle\Entity\Answer", mappedBy="question", cascade={"persist"})
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity="GameBundle\Entity\Subject", inversedBy="questions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $subject;  

    /**
     * @ORM\ManyToOne(targetEntity="GameBundle\Entity\SchoolClass", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $schoolClass;        

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
     * @ORM\Column(name="question", type="string", length=255)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="difficulty", type="string", length=255)
     */
    private $difficulty;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_valid", type="boolean")
     */
    private $isValid;


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
     * Set question
     *
     * @param string $question
     *
     * @return Question
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set difficulty
     *
     * @param string $difficulty
     *
     * @return Question
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * Get difficulty
     *
     * @return string
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * Set isValid
     *
     * @param boolean $isValid
     *
     * @return Question
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return bool
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * Set userCount
     *
     * @param \UserBundle\Entity\UserCount $userCount
     *
     * @return Question
     */
    public function setUserCount(UserCount $userCount)
    {
        $this->userCount = $userCount;

        return $this;
    }

    /**
     * Get userCount
     *
     * @return \UserBundle\Entity\UserCount
     */
    public function getUserCount()
    {
        return $this->userCount;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    /**
     * Add answer
     *
     * @param \GameBundle\Entity\Answer $answer
     *
     * @return Question
     */
    public function addAnswer(Answer $answer)
    {
        $this->answers[] = $answer;

        $answer->setQuestion($this);

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \GameBundle\Entity\Answer $answer
     */
    public function removeAnswer(Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set subject
     *
     * @param \GameBundle\Entity\Subject $subject
     *
     * @return Question
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

    /**
     * Set schoolClass
     *
     * @param \GameBundle\Entity\SchoolClass $schoolClass
     *
     * @return Question
     */
    public function setSchoolClass(SchoolClass $schoolClass)
    {
        $this->schoolClass = $schoolClass;

        return $this;
    }

    /**
     * Get schoolClass
     *
     * @return \GameBundle\Entity\SchoolClass
     */
    public function getSchoolClass()
    {
        return $this->schoolClass;
    }
}
