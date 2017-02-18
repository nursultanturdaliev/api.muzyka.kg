<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/18/17
 * Time: 12:23 AM
 */

namespace AppBundle\Tests\Entity;


use AppBundle\Entity\Artist;
use AppBundle\Entity\Song;
use PHPUnit\Framework\TestCase;

class ArtistTest extends TestCase
{
    /** @var  Artist */
    private $artist;

    /** @var  Song */
    private $song;

    private $date;

    protected function setUp()
    {
        $this->date = new \DateTime('now');
        $this->song = new Song();

        $this->artist = new Artist();
        $this->artist->setName('Nursultan');
        $this->artist->setLastname('Turdaliev');
        $this->artist->setBiography('Biography');
        $this->artist->setDebut($this->date);
        $this->artist->setGender('M');
        $this->artist->setBirthday($this->date);
        $this->artist->setId(1);
        $this->artist->setEmail('nursultan@turdaliev.com');
    }

    public function testArtist()
    {
        $this->assertEquals($this->artist->getName(), 'Nursultan');
        $this->assertEquals($this->artist->getLastname(), 'Turdaliev');
        $this->assertEquals($this->artist->getBiography(), 'Biography');
        $this->assertEquals($this->artist->getDebut(), $this->date);
        $this->assertEquals($this->artist->getEmail(), 'nursultan@turdaliev.com');
        $this->assertEquals($this->artist->getBirthday(), $this->date);
        $this->assertEquals($this->artist->getId(), 1);
        $this->assertEquals($this->artist->getGender(), 'M');
    }

    public function testAddSong()
    {
        $this->assertFalse($this->artist->getSongs()->contains($this->song));

        $this->artist->addSong($this->song);

        $this->assertTrue($this->artist->getSongs()->contains($this->song));
    }

    public function testRemoveSong()
    {
        $this->assertFalse($this->artist->getSongs()->contains($this->song));

        $this->artist->addSong($this->song);

        $this->assertTrue($this->artist->getSongs()->contains($this->song));

        $this->artist->removeSong($this->song);

        $this->assertFalse($this->artist->getSongs()->contains($this->song));

    }

}