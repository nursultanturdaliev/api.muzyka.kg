<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/17/17
 * Time: 9:12 PM
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Artist;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 *
 * Class LoadArtistData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadArtistData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{

	/**
	 * Load data fixtures with the passed EntityManager
	 *
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$data = [
			[
				'id'        => 1,
				'name'      => 'Самара',
				'lastname'  => 'Каримова',
				'birthday'  => new \DateTime('11-11-1990'),
				'debut'     => new \DateTime('01-01-2005'),
				'instagram' => 'https://www.instagram.com/samarakarimova_official/',
				'profile'   =>'https://scontent-frt3-2.cdninstagram.com/t51.2885-19/s320x320/19986082_2041740165852082_2464042528924499968_a.jpg',
				'songs'     =>
					[
						'ead5ab32-11c1-4771-8fdc-8764a4109adc',
						'e0dc01f8-cfdd-4ba3-bab7-8d8f6511e0a5',
						'1bf85ee0-1510-40ff-b7d9-86bbf0318ff4',
						'd214ec6b-e5ae-43f1-a56d-6686cd5ac0a3'
					],
			],
			[
				'id'        => 2,
				'name'      => 'Нурлан',
				'lastname'  => 'Насип',
				'birthday'  => new \DateTime('23-08-1990'),
				'debut'     => new \DateTime('01-04-2010'),
				'songs'     => [
					'ead5ab32-11c1-4771-8fdc-8764a4109adc',
					'e849bcb0-c9de-493e-b65f-f6f9f4cd7539',
					'c461ebe3-eaf5-461f-aa16-d768728dc18d',
					'24aa3472-1fed-4aed-ac25-f6fe38378a16'
				],
				'instagram' => 'https://www.instagram.com/nurlannasip_official/',
				'profile' => 'https://scontent-frt3-2.cdninstagram.com/t51.2885-19/s320x320/18947371_1699687010333397_5507363613641277440_a.jpg'
			]
		];
		foreach ($data as $item) {
			$artist = new Artist();
			$artist->setId($item['id']);
			$artist->setName($item['name']);
			$artist->setLastname($item['lastname']);
			$artist->setBirthday($item['birthday']);
			$artist->setDebut($item['debut']);
			$artist->setProfile($item['profile']);
			$artist->setInstagram($item['instagram']);

			foreach ($item['songs'] as $songUUID) {
				$artist->addSong($this->getReference($songUUID));
			}

			$manager->persist($artist);
			$manager->flush();
		}
	}

	/**
	 * Get the order of this fixture
	 *
	 * @return integer
	 */
	public function getOrder()
	{
		return 1;
	}
}