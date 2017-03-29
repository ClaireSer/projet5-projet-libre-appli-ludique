<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

// use UserBundle\Entity\UserInterface;
// use Serializable;
// use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
// use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
// use Symfony\Component\Security\Core\Role\Role;
// use Symfony\Component\Validator\Constraints as Assert;


/**
 * UserCount
 *
 * @ORM\Table(name="user_count")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserCountRepository")
 */
class UserCount implements UserInterface
{
    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\Gamer", mappedBy="UserCount")
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
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role = 'famille';

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
        $this->gamers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
 
        return array_unique($roles);
    }


    public function eraseCredentials() {
    
    }
}
