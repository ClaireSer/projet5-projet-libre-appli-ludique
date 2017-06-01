<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\SubjectRepository")
 * @UniqueEntity(fields="nameSubject", message="Le thème existe déjà.")
 */
class Subject
{

    /**
     * @ORM\OneToMany(targetEntity="GameBundle\Entity\Topic", mappedBy="subject", cascade={"remove"})
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
     * @ORM\Column(name="nameSubject", type="string", length=255, unique=true)
     */
    private $nameSubject;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->topics = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Subject
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
