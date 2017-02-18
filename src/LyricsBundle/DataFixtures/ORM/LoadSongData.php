<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/18/17
 * Time: 12:53 AM
 */

namespace LyricsBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LyricsBundle\Entity\Song;

/**
 *
 * @codeCoverageIgnore
 *
 * Class LoadSongData
 * @package LyricsBundle\DataFixtures\ORM
 */
class LoadSongData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $song = new Song();
        $song->setName('Kyrgyzstan');
        $song->setArtist($this->getReference('a-nursultan'));
        $song->setContent('Content');

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
        return 1;
    }
}