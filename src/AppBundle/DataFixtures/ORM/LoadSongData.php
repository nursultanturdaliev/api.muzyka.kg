<?php
/**
 * Created by PhpStorm.
 * User nursultan
 * Date 11/25/16
 * Time 2:31 AM
 */

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Song;
use AppBundle\Entity\Artist;

/**
 * @codeCoverageIgnore
 *
 * Class LoadSongData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadSongData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $songs = array(
            array(
                'artist'=>$this->getReference('nursultan'),
                'title' => 'Kyrgyzstan',
                'duration' => '04:00',
                'count_play' => 100,
                'likes' => 100,
                'lyrics' => 'Lyrics',
                'downloadable' => true,
                'count_download' => true,
                'published' => true,
                'uuid' => 'c25a07b8-15e2-481b-a9be-8aa962d811e4'
            ),
            array(
                'artist'=>$this->getReference('nursultan'),
                'title' => 'Atameken',
                'duration' => '02:00',
                'count_play' => 100,
                'likes' => 100,
                'lyrics' => 'Atamekinim',
                'downloadable' => true,
                'count_download' => true,
                'published' => true,
                'uuid' => 'c25a07b8-15e2-481b-a9be-8aa962d811e2'
            ),
            array(
                'artist'=>$this->getReference('sheki'),
                'title' => 'Bishkek',
                'duration' => '03:10',
                'count_play' => 120,
                'likes' => 120,
                'lyrics' => 'Bishkegim',
                'downloadable' => true,
                'count_download' => true,
                'published' => true,
                'uuid' => 'c25a07b8-15e2-481b-a9be-8aa962d811e1'
            ),
            array(
                'artist'=>$this->getReference('sheki'),
                'title' => 'Saga',
                'duration' => '03:20',
                'count_play' => 120,
                'likes' => 120,
                'lyrics' => 'Saga',
                'downloadable' => true,
                'count_download' => true,
                'published' => true,
                'uuid' => 'c25a07b8-15e2-481b-a9be-8aa962d811e3'
            )
        );
        foreach ($songs as $s) {

            $song = new Song();
            $song->setArtist($s['artist'])
                ->setTitle($s['title'])
                ->setDuration($s['duration'])
                ->setCountPlay($s['count_play'])
                ->setLikes($s['likes'])
                ->setLyrics($s['lyrics'])
                ->setDownloadable($s['downloadable'])
                ->setCountDownload($s['count_download'])
                ->setPublished($s['published'])
                ->setUuid($s['uuid']);
            $manager->persist($song);
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

        return 2;
    }
}
