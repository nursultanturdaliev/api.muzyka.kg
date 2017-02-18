<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/5/17
 * Time: 5:03 PM
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Genre;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 *
 * Class LoadGenreData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadGenreData implements FixtureInterface, OrderedFixtureInterface
{
    private $genres = array(
        'Джаз',
        'Классика',
        'Поп',
        'Рок',
        'Рэп',
        'Хип-хоп',
        'Фольклор',
        'Эстрада',
    );

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->genres as $name) {
            $genre = new Genre();
            $genre->setName($name);
            $manager->persist($genre);
        }
        $manager->flush();
    }
}