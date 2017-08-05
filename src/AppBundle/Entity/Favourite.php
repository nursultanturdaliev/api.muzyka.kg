<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Favourite
 *
 * @ORM\Table(name="app_favourite", uniqueConstraints={@UniqueConstraint(name="user_song_unique_index", columns={"user_id","song_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FavouriteRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Favourite
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
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="favourites")
	 *
	 */
	private $user;

	/**
	 * @ORM\ManyToOne(targetEntity="Song", inversedBy="favourites")
	 */
	private $song;


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
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * @param User $user
	 *
	 * @return Favourite
	 */
	public function setUser($user)
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Set song
	 *
	 * @param Song $song
	 *
	 * @return Favourite
	 */
	public function setSong(Song $song = null)
	{
		$this->song = $song;

		return $this;
	}

	/**
	 * Get song
	 *
	 * @return Song
	 */
	public function getSong()
	{
		return $this->song;
	}
}
