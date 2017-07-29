<?php

namespace AppBundle\Entity;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Playlist
 *
 * @ORM\Table(name="app_playlists")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlaylistRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Playlist
{
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
	 * @ORM\Column(name="name", type="string", length=100)
	 */
	private $name;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="createdAt", type="datetime")
	 */
	private $createdAt;

	/**
	 * @ORM\Column(name="updated_at", type="datetime")
	 */
	private $updatedAt;

	/**
	 * @ORM\ManyToMany(targetEntity="Song", inversedBy="playlists")
	 */
	private $songs;


	/**
	 * Get id
	 *
	 * @codeCoverageIgnore
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
	 * @return Playlist
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
	 * Set createdAt
	 *
	 * @param \DateTime $createdAt
	 *
	 * @return Playlist
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
	 * Constructor
	 */
	public function __construct()
	{
		$this->songs = new ArrayCollection();
	}

	/**
	 * Add song
	 *
	 * @param Song $song
	 *
	 * @return Playlist
	 */
	public function addSong(Song $song)
	{
		$this->songs->add($song);
		$song->addPlaylist($this);

		return $this;
	}

	/**
	 * Remove song
	 *
	 * @param Song $song
	 */
	public function removeSong(Song $song)
	{
		$this->songs->removeElement($song);
	}

	/**
	 * Get songs
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getSongs()
	{
		return $this->songs;
	}

	/**
	 * @codeCoverageIgnore
	 *
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->createdAt = new \DateTime();
	}

	/**
	 * @codeCoverageIgnore
	 *
	 * @ORM\PreUpdate()
	 */
	public function preUpdate()
	{
		$this->updatedAt = new \DateTime();
	}

	/**
	 * Set updatedAt
	 *
	 * @param \DateTime $updatedAt
	 *
	 * @return Playlist
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
}
