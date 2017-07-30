<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 11/25/16
 * Time: 2:28 AM
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 *
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserData implements FixtureInterface, OrderedFixtureInterface
{

	/**
	 * Load data fixtures with the passed EntityManager
	 *
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$userAdmin = new User();
		$userAdmin->setUsername('admin');
		$userAdmin->setSuperAdmin(true);
		$userAdmin->setEnabled(true);
		$userAdmin->setEmail('nursultan2010@gmail.com');
		$userAdmin->setPlainPassword('admin');

		$manager->persist($userAdmin);
		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	public function getOrder()
	{
		return 0;
	}


}