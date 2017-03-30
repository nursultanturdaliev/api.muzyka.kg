<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/17/17
 * Time: 9:12 PM
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Artist;
use AppBundle\Entity\Song;
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
		$data = array(
			array(
				'id'       => 1,
				'name'     => 'Nursultan',
				'lastname' => 'Turdaliev',
				'birthday' => new \DateTime('11-11-1992'),
				'debut'    => new \DateTime('01-01-2016'),
				'songs'    =>
					array(
						array(
							'title'          => 'Kyrgyzstan',
							'duration'       => '04:00',
							'count_play'     => 100,
							'likes'          => 100,
							'lyrics'         => 'Lyrics',
							'downloadable'   => true,
							'count_download' => 1,
							'published'      => true,
							'published_at'   => new \DateTime('01-04-2016'),
							'uuid'           => 'c25a07b8-15e2-481b-a9be-8aa962d811e4'
						),
						array(
							'title'          => 'Atameken',
							'duration'       => '02:00',
							'count_play'     => 101,
							'likes'          => 102,
							'lyrics'         => 'Atamekinim',
							'downloadable'   => true,
							'count_download' => 2,
							'published'      => true,
							'published_at'   => new \DateTime('01-01-2016'),
							'uuid'           => 'c25a07b8-15e2-481b-a9be-8aa962d811e2'
						))
			),
			array(
				'id'       => 2,
				'name'     => 'Sherkazy',
				'lastname' => 'Kokumbaev',
				'birthday' => new \DateTime('23-08-1992'),
				'debut'    => new \DateTime('01-04-2016'),
				'songs'    =>
					array(
						array(
							'title'          => 'Bishkek',
							'duration'       => '03:10',
							'count_play'     => 122,
							'likes'          => 123,
							'lyrics'         => 'Bishkegim',
							'downloadable'   => true,
							'count_download' => 3,
							'published'      => true,
							'published_at'   => new \DateTime('01-02-2016'),
							'uuid'           => 'c25a07b8-15e2-481b-a9be-8aa962d811e1'
						),
						array(
							'title'          => 'Saga',
							'duration'       => '03:20',
							'count_play'     => 123,
							'likes'          => 124,
							'lyrics'         => 'Saga',
							'downloadable'   => true,
							'count_download' => 4,
							'published'      => true,
							'published_at'   => new \DateTime('01-01-2015'),
							'uuid'           => 'c25a07b8-15e2-481b-a9be-8aa962d811e3'
						))
			)
		);
		foreach ($data as $item) {
			$artist = new Artist();
			$artist->setId($item['id']);
			$artist->setName($item['name']);
			$artist->setLastname($item['lastname']);
			$artist->setBirthday($item['birthday']);
			$artist->setDebut($item['debut']);

			foreach ($item['songs'] as $s) {
				$song = new Song();
				$song->setTitle($s['title'])
					 ->setDuration($s['duration'])
					 ->setCountPlay($s['count_play'])
					 ->setLikes($s['likes'])
					 ->setLyrics($s['lyrics'])
					 ->setDownloadable($s['downloadable'])
					 ->setCountDownload($s['count_download'])
					 ->setPublished($s['published'])
					 ->setPublishedAt($s['published_at'])
					 ->setUuid($s['uuid']);
				$artist->addSong($song);
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
		return 0;
	}
}