<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Song
 *
 * @ORM\Table(name="song")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SongRepository")
 */
class Song
{
    /**
     * @ORM\Column(name="uuid", type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Id()
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="artist", type="string", length=255)
     */
    private $artist;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=255)
     */
    private $duration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishedAt", type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="singer_id", referencedColumnName="id" )
     * })
     */
    private $singer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="download_able", type="boolean", nullable=true)
     */
    private $downloadable;

    /**
     * @var integer
     *
     * @ORM\Column(name="count_download", type="integer", nullable=true)
     */
    private $countDownload;

    /**
     * @var integer
     *
     * @ORM\Column(name="count_play", type="integer", nullable=true)
     */
    private $countPlay;

    /**
     * @var integer
     *
     * @ORM\Column(name="likes", type="integer", nullable=true)
     */
    private $likes;

    /**
     * @var string
     *
     * @ORM\Column(name="lyrics", type="text", nullable=true)
     */
    private $lyrics;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="upload_date", type="datetime", nullable=true)
     */
    private $uploadDate;

    public function __construct()
    {
        $this->downloadable = false;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Song
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set duration
     *
     * @param string $duration
     * @return Song
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return Song
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Song
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getSinger()
    {
        return $this->singer;
    }

    /**
     * @param mixed $singer
     */
    public function setSinger($singer)
    {
        $this->singer = $singer;
    }

    /**
     * @return boolean
     */
    public function isDownloadable()
    {
        return $this->downloadable;
    }

    /**
     * @param boolean $downloadable
     */
    public function setDownloadable($downloadable)
    {
        $this->downloadable = $downloadable;
    }

    /**
     * @return int
     */
    public function getCountDownload()
    {
        return $this->countDownload;
    }

    /**
     * @param int $countDownload
     */
    public function setCountDownload($countDownload)
    {
        $this->countDownload = $countDownload;
    }

    /**
     * @return int
     */
    public function getCountPlay()
    {
        return $this->countPlay;
    }

    /**
     * @param int $countPlay
     */
    public function setCountPlay($countPlay)
    {
        $this->countPlay = $countPlay;
    }

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return string
     */
    public function getLyrics()
    {
        return $this->lyrics;
    }

    /**
     * @param string $lyrics
     */
    public function setLyrics($lyrics)
    {
        $this->lyrics = $lyrics;
    }

    /**
     * @return \DateTime
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * @param \DateTime $uploadDate
     */
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;
    }

    /**
     * @return int
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param int $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

}
