<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/18/17
 * Time: 1:18 AM
 */

namespace LyricsBundle\Tests\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use LyricsBundle\Entity\Artist;
use LyricsBundle\Entity\Song;
use PHPUnit\Framework\TestCase;

class ArtistTest extends TestCase
{
    /** @var  Artist */
    private $artist;

    /** @var  \DateTime */
    private $date;

    protected function setUp()
    {
        $this->date = new \DateTime('now');
        $this->artist = new Artist();
        $this->artist->setName('Nursultan');
        $this->artist->setCreatedAt($this->date);
        $this->artist->setUpdatedAt($this->date);
    }

    public function testArtist()
    {
        $this->assertEquals($this->artist->getName(), 'Nursultan');
        $this->assertEquals($this->artist->getUpdatedAt(), $this->date);
        $this->assertEquals($this->artist->getCreatedAt(), $this->date);
    }

    public function testAddSongs()
    {
        $song = new Song();
        $this->assertEquals($this->artist->getSongs()->count(), 0);

        $this->artist->addSong($song);

        $this->assertEquals($this->artist->getSongs()->count(), 1);
        $this->assertTrue($this->artist->getSongs()->contains($song));
    }

    public function testRemoveSongs()
    {

        $song = new Song();
        $this->assertEquals($this->artist->getSongs()->count(), 0);

        $this->artist->addSong($song);

        $this->assertTrue($this->artist->getSongs()->contains($song));

        $this->artist->removeSong($song);

        $this->assertFalse($this->artist->getSongs()->contains($song));
    }

    public function testSetSongs()
    {
        $songA = new Song();
        $songB = new Song();

        $songs = new ArrayCollection(array($songA, $songB));

        $this->artist->setSongs($songs);

        $this->assertTrue($this->artist->getSongs()->contains($songA));
    }

}