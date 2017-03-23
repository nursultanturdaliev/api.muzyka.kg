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
        $song = new Song();
        $song->setArtist($this->getReference('nursultan'))
            ->setTitle('Kyrgyzstan')
            ->setDuration('04:00')
            ->setCountPlay(1000)
            ->setLikes(1000)
            ->setLyrics('Lyrics')
            ->setDownloadable(true)
            ->setCountDownload(0)
            ->setOldUrl('')
            ->setPublished(true)
            ->setUuid('c25a07b8-15e2-481b-a9be-8aa962d811e4');
        $manager->persist($song);
        $manager->flush();
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
