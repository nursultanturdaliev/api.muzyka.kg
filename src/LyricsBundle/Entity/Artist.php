<?php

namespace LyricsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * Artist
 *
 * @ORM\Table(name="app_artist",schema="lyrics")
 * @ORM\Entity(repositoryClass="LyricsBundle\Repository\SongRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ExclusionPolicy("All")
 */
class Artist
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Expose()
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     * @Expose()
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime",nullable=true)
     * @Expose()
     */
    private $updatedAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LyricsBundle\Entity\Song", mappedBy="artist", cascade={"persist"}, fetch="LAZY")
     * @Expose()
     * @MaxDepth(1)
     *
     */
    private $songs;

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
     * Constructor
     */
    public function __construct()
    {
        $this->songs = new ArrayCollection();
    }

    /**
     * Add song
     *
     * @param Artist $song
     *
     * @return Artist
     */
    public function addSong(Artist $song)
    {
        $this->songs[] = $song;

        return $this;
    }

    /**
     * Remove song
     *
     * @param Artist $song
     */
    public function removeSong(Artist $song)
    {
        $this->songs->removeElement($song);
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Artist
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param \DateTime $createdAt
     * @return Artist
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @param mixed $songs
     * @return Artist
     */
    public function setSongs($songs)
    {
        $this->songs = $songs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSongs()
    {
        return $this->songs;
    }
}

