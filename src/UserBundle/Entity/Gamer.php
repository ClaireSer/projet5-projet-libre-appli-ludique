<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gamer
 *
 * @ORM\Table(name="gamer")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\GamerRepository")
 */
class Gamer
{
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\UserCount", inversedBy="gamers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userCount;

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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
     * @var int
     *
     * @ORM\Column(name="best_score", type="integer")
     */
    private $bestScore;

    /**
     * @var int
     *
     * @ORM\Column(name="game_won_nb", type="integer")
     */
    private $gameWonNb;

    /**
     * @var int
     *
     * @ORM\Column(name="right_answer_nb", type="integer")
     */
    private $rightAnswerNb;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="smallint")
     */
    private $level;


    /**
     * Set userCount
     *
     * @param \UserBundle\Entity\UserCount $userCount
     *
     * @return Gamer
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Gamer
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Gamer
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Gamer
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set bestScore
     *
     * @param integer $bestScore
     *
     * @return Gamer
     */
    public function setBestScore($bestScore)
    {
        $this->bestScore = $bestScore;

        return $this;
    }

    /**
     * Get bestScore
     *
     * @return int
     */
    public function getBestScore()
    {
        return $this->bestScore;
    }

    /**
     * Set gameWonNb
     *
     * @param integer $gameWonNb
     *
     * @return Gamer
     */
    public function setGameWonNb($gameWonNb)
    {
        $this->gameWonNb = $gameWonNb;

        return $this;
    }

    /**
     * Get gameWonNb
     *
     * @return int
     */
    public function getGameWonNb()
    {
        return $this->gameWonNb;
    }

    /**
     * Set rightAnswerNb
     *
     * @param integer $rightAnswerNb
     *
     * @return Gamer
     */
    public function setRightAnswerNb($rightAnswerNb)
    {
        $this->rightAnswerNb = $rightAnswerNb;

        return $this;
    }

    /**
     * Get rightAnswerNb
     *
     * @return int
     */
    public function getRightAnswerNb()
    {
        return $this->rightAnswerNb;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Gamer
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
}
