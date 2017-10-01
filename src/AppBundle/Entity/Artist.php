<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Artist
 *
 * @ORM\Table(name="app_artists")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtistRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
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
	 * @ORM\Column(name="name", type="string", length=100)
	 * @Expose()
	 */
	private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100, unique=false, nullable=true)
     */
    private $slug;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="lastname", type="string", length=100, nullable=true)
	 * @Expose()
	 */
	private $lastname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="birthday", type="date", nullable=true)
	 */
	private $birthday;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="gender", type="string", length=1, nullable=true)
	 * @Expose()
	 */
	private $gender;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="debut", type="date", nullable=true)
	 */
	private $debut;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=100, nullable=true, unique=true)
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="biography", type="text", nullable=true)
	 */
	private $biography;

	/**
	 * @var string
	 * @ORM\Column(name="instagram", type="string", nullable=true)
	 * @Expose()
	 */
	private $instagram;

	/**
	 * @var
	 * @ORM\Column(name="profile", type="string", nullable=true)
	 * @Expose()
	 */
	private $profile;

	/**
	 * @var
	 * @ORM\Column(name="profile_local", type="text", nullable=true)
	 * @Expose()
	 */
	private $profileLocal;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Song", inversedBy="artists", cascade={"persist"}, fetch="LAZY")
	 * @ORM\JoinTable(name="app_artist_song")
	 * @Expose()
	 */
	private $songs;

	/**
	 * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
	 */
	private $deletedAt;

	public function __construct()
	{
        $this->profileLocal = null;
		$this->songs = new ArrayCollection();
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
	 * Set lastname
	 *
	 * @param string $lastname
	 *
	 * @return Artist
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
	 * Set birthday
	 *
	 * @param \DateTime $birthday
	 *
	 * @return Artist
	 */
	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;

		return $this;
	}

	/**
	 * Get birthday
	 *
	 * @return string
	 */
	public function getBirthday()
	{
		return $this->birthday;
	}

	/**
	 * Set gender
	 *
	 * @param string $gender
	 *
	 * @return Artist
	 */
	public function setGender($gender)
	{
		$this->gender = $gender;

		return $this;
	}

	/**
	 * Get gender
	 *
	 * @return string
	 */
	public function getGender()
	{
		return $this->gender;
	}

	/**
	 * Set debut
	 *
	 * @param \DateTime $debut
	 *
	 * @return Artist
	 */
	public function setDebut($debut)
	{
		$this->debut = $debut;

		return $this;
	}

	/**
	 * Get debut
	 *
	 * @return \DateTime
	 */
	public function getDebut()
	{
		return $this->debut;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 *
	 * @return Artist
	 */
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set biography
	 *
	 * @param string $biography
	 *
	 * @return Artist
	 */
	public function setBiography($biography)
	{
		$this->biography = $biography;

		return $this;
	}

	/**
	 * Get biography
	 *
	 * @return string
	 */
	public function getBiography()
	{
		return $this->biography;
	}

	/**
	 * Add song
	 *
	 * @param Song $song
	 *
	 * @return Artist
	 */
	public function addSong(Song $song)
	{
		$song->addArtist($this);
		$this->songs->add($song);

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

	function __toString()
	{
		return $this->name . ' ' . $this->lastname;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Set instagram
	 *
	 * @param string $instagram
	 *
	 * @return Artist
	 */
	public function setInstagram($instagram)
	{
		$this->instagram = $instagram;

		return $this;
	}

	/**
	 * Get instagram
	 *
	 * @return string
	 */
	public function getInstagram()
	{
		return $this->instagram;
	}

	/**
	 * @return mixed
	 */
	public function getProfile()
	{
		return $this->profile;
	}

	/**
	 * @param string $profile
	 *
	 * @return $this
	 */
	public function setProfile($profile)
	{
		$this->profile = $profile;

		return $this;
	}

	/**
	 * @param mixed $profileLocal
	 *
	 * @return Artist
	 */
	public function setProfileLocal($profileLocal)
	{
		$this->profileLocal = $profileLocal;
		return $this;
	}

	public function hasProfileLocal()
	{
		return boolval($this->profileLocal);
	}

	/**
	 * @return mixed
	 */
	public function getProfileLocal()
	{
        return $this->profileLocal;
	}

	/**
	 * @param mixed $deletedAt
	 *
	 * @return Artist
	 */
	public function setDeletedAt($deletedAt)
	{
		$this->deletedAt = $deletedAt;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDeletedAt()
	{
		return $this->deletedAt;
	}

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
