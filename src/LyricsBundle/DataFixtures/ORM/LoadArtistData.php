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
use LyricsBundle\Entity\Artist;

/**
 *
 * @codeCoverageIgnore
 *
 * Class LoadArtistData
 * @package LyricsBundle\DataFixtures\ORM
 */
class LoadArtistData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $artist = new Artist();
        $artist->setName('Nursultan');

        $manager->persist($artist);
        $manager->flush();

        $this->addReference('a-nursultan', $artist);
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