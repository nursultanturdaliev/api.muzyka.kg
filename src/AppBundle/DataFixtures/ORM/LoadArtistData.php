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
        $artist = new Artist();
        $artist->setId(1);
        $artist->setName('Nursultan');
        $artist->setLastname('Turdaliev');
        $artist->setBirthday(new \DateTime('11-11-1992'));
        $artist->setDebut(new \DateTime('01-01-2016'));

        $manager->persist($artist);
        $manager->flush();

        $this->addReference('nursultan', $artist);
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