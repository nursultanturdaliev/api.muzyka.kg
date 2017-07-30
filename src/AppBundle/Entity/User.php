<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 7/29/17
 * Time: 2:32 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RCH\JWTUserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Favourite", mappedBy="user")
	 *
	 */
	private $favourites;

	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\History", mappedBy="user")
	 */
	private $histories;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param mixed $favourites
	 *
	 * @return User
	 */
	public function setFavourites($favourites)
	{
		$this->favourites = $favourites;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFavourites()
	{
		return $this->favourites;
	}

	/**
	 * Add favourite
	 *
	 * @param \AppBundle\Entity\Favourite $favourite
	 *
	 * @return User
	 */
	public function addFavourite(\AppBundle\Entity\Favourite $favourite)
	{
		$this->favourites[] = $favourite;

		return $this;
	}

	/**
	 * Remove favourite
	 *
	 * @param \AppBundle\Entity\Favourite $favourite
	 */
	public function removeFavourite(\AppBundle\Entity\Favourite $favourite)
	{
		$this->favourites->removeElement($favourite);
	}

	/**
	 * @param mixed $histories
	 *
	 * @return User
	 */
	public function setHistories($histories)
	{
		$this->histories = $histories;
		return $this;
	}

    /**
     * Add history
     *
     * @param \AppBundle\Entity\History $history
     *
     * @return User
     */
    public function addHistory(\AppBundle\Entity\History $history)
    {
        $this->histories[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param \AppBundle\Entity\History $history
     */
    public function removeHistory(\AppBundle\Entity\History $history)
    {
        $this->histories->removeElement($history);
    }

    /**
     * Get histories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistories()
    {
        return $this->histories;
    }
}
