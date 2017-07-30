<?php

namespace LyricsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Song
 *
 * @ORM\Table(name="app_song",schema="lyrics")
 * @ORM\Entity(repositoryClass="LyricsBundle\Repository\SongRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ExclusionPolicy("All")
 */
class Song
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
     * @ORM\Column(name="name", type="text")
     * @Expose()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Expose()
     */
    private $content;

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
     * @var Artist
     *
     * @ORM\ManyToOne(targetEntity="LyricsBundle\Entity\Artist", inversedBy="songs")
     */
    private $artist;

    /**
     *
     * @codeCoverageIgnore
     *
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Song
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
     * Set content
     *
     * @param string $content
     *
     * @return Song
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     *
     * @codeCoverageIgnore
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     *
     * @codeCoverageIgnore
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @param Artist $artist
     * @return Song
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
        return $this;
    }

    /**
     * @return Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param \DateTime $createdAt
     * @return Song
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
     * @param mixed $updatedAt
     * @return Song
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

