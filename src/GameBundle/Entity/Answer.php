<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @ORM\ManyToOne(targetEntity="GameBundle\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

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
     * @ORM\Column(name="answer", type="string", length=255)
     */
    private $answer;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_right", type="boolean")
     */
    private $isRight = false;


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
     * Set answer
     *
     * @param string $answer
     *
     * @return Answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set isRight
     *
     * @param boolean $isRight
     *
     * @return Answer
     */
    public function setIsRight($isRight)
    {
        $this->isRight = $isRight;

        return $this;
    }

    /**
     * Get isRight
     *
     * @return bool
     */
    public function getIsRight()
    {
        return $this->isRight;
    }

    /**
     * Set question
     *
     * @param \GameBundle\Entity\Question $question
     *
     * @return Answer
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \GameBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
