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

	public function __construct()
	{
		parent::__construct();
	}
}