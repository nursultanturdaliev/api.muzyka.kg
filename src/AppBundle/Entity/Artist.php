<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Artist
 *
 * @ORM\Table(name="app_artists")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtistRepository")
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Song", mappedBy="artist", cascade={"persist"}, fetch="LAZY")
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
     * @return Artist
     */
    public function addSong(Song $song)
    {
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
}
