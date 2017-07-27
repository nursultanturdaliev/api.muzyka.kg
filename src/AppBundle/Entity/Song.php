<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use Ramsey\Uuid\Uuid;

/**
 * Song
 *
 * @ORM\Table(name="app_songs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SongRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ExclusionPolicy("All")
 */
class Song
{
	/**
	 * @ORM\Column(name="uuid", type="uuid")
	 * @Type("string")
	 * @Expose()
	 */
	private $uuid;


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
	 * @ORM\Column(name="title", type="string", length=255)
	 * @Expose()
	 */
	private $title;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="duration", type="string", length=255)
	 * @Expose()
	 */
	private $duration;

	/**
	 * @ORM\Column(name="published", type="boolean")
	 */
	private $published = true;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="published_at", type="datetime", nullable=true)
	 * @Expose()
	 */
	private $publishedAt;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="download_able", type="boolean", nullable=true)
	 */
	private $downloadable = true;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="count_download", type="integer", nullable=true)
	 * @Expose()
	 */
	private $countDownload = 0;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="count_play", type="integer", nullable=true)
	 * @Expose()
	 */
	private $countPlay = 0;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="likes", type="integer", nullable=true)
	 */
	private $likes = 0;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="lyrics", type="text", nullable=true)
	 * @Expose()
	 */
	private $lyrics;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="createdAt", type="datetime")
	 */
	private $createdAt;

	/**
	 * @ORM\Column(name="updated_at", type="datetime",nullable=true)
	 */
	private $updatedAt;

	/**
	 * @var Artist
	 *
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Artist", mappedBy="songs")
	 * @Expose()
	 */
	private $artists;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Genre",inversedBy="songs")
	 */
	private $genres;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Playlist", mappedBy="songs")
	 * @Exclude()
	 */
	private $playlists;

	/**
	 *
	 * @ORM\Column(type="boolean", name="is_new", nullable=true)
	 * @var bool $isNew
	 */
	private $isNew;

	public function __construct()
	{
		$this->artists   = new ArrayCollection();
		$this->genres    = new ArrayCollection();
		$this->playlists = new ArrayCollection();
		$this->uuid      = Uuid::uuid4();
		$this->isNew     = false;
	}

	/**
	 * Set title
	 *
	 * @param string $title
	 *
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
	 *
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
	 *
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
	 * @return PersistentCollection|Artist[]
	 */
	public function getArtists()
	{
		return $this->artists;
	}

	/**
	 * @param string $artists
	 *
	 * @return $this
	 */
	public function setArtists($artists)
	{
		$this->artists = $artists;
		return $this;
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
	 *
	 * @return $this
	 */
	public function setDownloadable($downloadable)
	{
		$this->downloadable = $downloadable;
		return $this;
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
	 *
	 * @return $this
	 */
	public function setCountDownload($countDownload)
	{
		$this->countDownload = $countDownload;
		return $this;
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
	 *
	 * @return $this
	 */
	public function setCountPlay($countPlay)
	{
		$this->countPlay = $countPlay;
		return $this;
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
	 *
	 * @return $this
	 */
	public function setLikes($likes)
	{
		$this->likes = $likes;
		return $this;
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
	 *
	 * @return $this
	 */
	public function setLyrics($lyrics)
	{
		$this->lyrics = $lyrics;
		return $this;
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
	 *
	 * @return $this
	 */
	public function setUuid($uuid)
	{
		$this->uuid = $uuid;
		return $this;
	}


	/**
	 * Get downloadable
	 *
	 * @return boolean
	 */
	public function getDownloadable()
	{
		return $this->downloadable;
	}

	/**
	 * Add genre
	 *
	 * @param Genre $genre
	 *
	 * @return Song
	 */
	public function addGenre(Genre $genre)
	{
		$this->genres->add($genre);
		return $this;
	}

	/**
	 * Remove genre
	 *
	 * @param Genre $genre
	 */
	public function removeGenre(Genre $genre)
	{
		$this->genres->removeElement($genre);
	}

	/**
	 * Get genres
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getGenres()
	{
		return $this->genres;
	}

	/**
	 * Add playlist
	 *
	 * @param Playlist $playlist
	 *
	 * @return Song
	 */
	public function addPlaylist(Playlist $playlist)
	{
		$this->playlists[] = $playlist;

		return $this;
	}

	/**
	 * Remove playlist
	 *
	 * @param Playlist $playlist
	 */
	public function removePlaylist(Playlist $playlist)
	{
		$this->playlists->removeElement($playlist);
	}

	/**
	 * Get playlists
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getPlaylists()
	{
		return $this->playlists;
	}

	/**
	 * Set published
	 *
	 * @param boolean $published
	 *
	 * @return Song
	 */
	public function setPublished($published)
	{
		$this->published = $published;

		return $this;
	}

	/**
	 * Get published
	 *
	 * @return boolean
	 */
	public function getPublished()
	{
		return $this->published;
	}

	/**
	 * Set createdAt
	 *
	 * @param \DateTime $createdAt
	 *
	 * @return Song
	 */
	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * Get createdAt
	 *
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * Set updatedAt
	 *
	 * @param \DateTime $updatedAt
	 *
	 * @return Song
	 */
	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}

	/**
	 * Get updatedAt
	 *
	 * @return \DateTime
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
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
	 * @@codeCoverageIgnore
	 *
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Add artist
	 *
	 * @param Artist $artist
	 *
	 * @return Song
	 */
	public function addArtist(Artist $artist)
	{
		$this->artists[] = $artist;

		return $this;
	}

	/**
	 * Remove artist
	 *
	 * @param Artist $artist
	 */
	public function removeArtist(Artist $artist)
	{
		$this->artists->removeElement($artist);
	}

	/**
	 * @VirtualProperty()
	 * @return string
	 */
	public function getArtistAsOne()
	{
		return implode(', ', array_map(function (Artist $artist) {
			return $artist->getName() . ' ' . $artist->getLastname();
		}, $this->getArtists()->toArray()));
	}

	/**
	 * @param boolean $isNew
	 *
	 * @return Song
	 */
	public function setIsNew($isNew)
	{
		$this->isNew = $isNew;
		return $this;
	}
}
