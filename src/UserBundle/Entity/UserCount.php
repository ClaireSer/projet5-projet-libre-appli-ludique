<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use GameBundle\Entity\Question;

/**
 * UserCount
 *
 * @ORM\Table(name="user_count")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserCountRepository")
 */
class UserCount implements UserInterface
{
    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\Gamer", mappedBy="userCount", cascade={"remove"})
     */
    private $gamers;

    /**
     * @ORM\OneToMany(targetEntity="GameBundle\Entity\Question", mappedBy="userCount", cascade={"remove"})
     */
    private $questions;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;
    
    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles = array();

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gamers = new ArrayCollection();
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
     * Set password
     *
     * @param string $password
     *
     * @return UserCount
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return UserCount
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

    public function eraseCredentials() {
    
    }

    /**
     * Add gamer
     *
     * @param \UserBundle\Entity\Gamer $gamer
     *
     * @return UserCount
     */
    public function addGamer(Gamer $gamer)
    {
        $this->gamers[] = $gamer;
        
        $gamer->setUserCount($this);

        return $this;
    }

    /**
     * Remove gamer
     *
     * @param \UserBundle\Entity\Gamer $gamer
     */
    public function removeGamer(Gamer $gamer)
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

    /**
     * Add question
     *
     * @param \GameBundle\Entity\Question $question
     *
     * @return UserCount
     */
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;

        $question->setUserCount($this);        

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
     * Set roles
     *
     * @param array $roles
     *
     * @return UserCount
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
