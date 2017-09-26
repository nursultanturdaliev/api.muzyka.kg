<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * History
 *
 * @ORM\Table(name="app_history")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HistoryRepository")
 */
class History
{
	const SESSION_ID = "history:";
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="uuid")
	 * @ORM\Id
	 */
	private $id;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="startedAt", type="datetime")
	 */
	private $startedAt;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="stoppedAt", type="datetime", nullable=true)
	 */
	private $stoppedAt;

	/**
	 * @var Song
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Song")
	 */
	private $song;

	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
	 * @var User
	 */
	private $user;

    /**
     * @var string
     * @ORM\Column(name="client_ip", type="string", nullable=true)
     */
    private $clientIp;


    public function __construct()
	{
		$this->id = Uuid::uuid4();
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
	 * Set startedAt
	 *
	 * @param \DateTime $startedAt
	 *
	 * @return History
	 */
	public function setStartedAt($startedAt)
	{
		$this->startedAt = $startedAt;

		return $this;
	}

	/**
	 * Get startedAt
	 *
	 * @return \DateTime
	 */
	public function getStartedAt()
	{
		return $this->startedAt;
	}

	/**
	 * Set stoppedAt
	 *
	 * @param \DateTime $stoppedAt
	 *
	 * @return History
	 */
	public function setStoppedAt($stoppedAt)
	{
		$this->stoppedAt = $stoppedAt;

		return $this;
	}

	/**
	 * Get stoppedAt
	 *
	 * @return \DateTime
	 */
	public function getStoppedAt()
	{
		return $this->stoppedAt;
	}

	/**
	 * @param Song $song
	 *
	 * @return History
	 */
	public function setSong($song)
	{
		$this->song = $song;
		return $this;
	}

	/**
	 * @return Song
	 */
	public function getSong()
	{
		return $this->song;
	}

	/**
	 * @param User $user
	 *
	 * @return History
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
	 * @return bool
	 */
	public function acceptAsListened()
	{
		$startedAt     = $this->getStartedAt()->getTimestamp();
		$stoppedAt     = $this->getStoppedAt()->getTimestamp();
		$diffInSeconds = $stoppedAt - $startedAt;
		return ($diffInSeconds) > 60 && $diffInSeconds < 1800;
	}

    /**
     * Set id
     *
     * @param uuid $id
     *
     * @return History
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * @param string $clientIp
     */
    public function setClientIp($clientIp)
    {
        $this->clientIp = $clientIp;
    }
}
