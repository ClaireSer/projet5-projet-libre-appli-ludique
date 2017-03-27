<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserCount
 *
 * @ORM\Table(name="user_count")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\UserCountRepository")
 */
class UserCount
{
    /**
     * @ORM\OneToMany(targetEntity="GameBundle\Entity\Gamer", mappedBy="UserCount")
     */
    protected $gamers;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    protected $role = 'famille';


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gamers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add gamer
     *
     * @param \GameBundle\Entity\Gamer $gamer
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
     * @param \GameBundle\Entity\Gamer $gamer
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return UserCount
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return UserCount
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

   
}
