<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/17/17
 * Time: 11:44 PM
 */

namespace AppBundle\Tests\Entity;


use AppBundle\Entity\Genre;
use AppBundle\Entity\Song;
use PHPUnit\Framework\TestCase;

class GenreTest extends TestCase
{
    /** @var  Genre */
    private $genre;

    protected function setUp()
    {
        $this->genre = new Genre();
        $this->genre->setName('Classics');
    }

    protected function assertPreConditions()
    {
        $this->assertEquals($this->genre->getSongs()->count(), 0);
        $this->assertEquals($this->genre->getName(), 'Classics');
    }


    public function testAddSong()
    {
        $song = new Song();

        $this->genre->addSong($song);

        $this->assertTrue($this->genre->getSongs()->contains($song));
        $this->assertEquals($this->genre->getSongs()->count(), 1);
    }

    public function testRemoveSong()
    {
        $song = new Song();

        $this->assertFalse($this->genre->getSongs()->contains($song));
        $this->assertEquals($this->genre->getSongs()->count(), 0);
        $this->genre->addSong($song);

        $this->assertTrue($this->genre->getSongs()->contains($song));
        $this->assertEquals($this->genre->getSongs()->count(), 1);

        $this->genre->removeSong($song);

        $this->assertEquals($this->genre->getSongs()->count(), 0);
        $this->assertFalse($this->genre->getSongs()->contains($song));
    }
}