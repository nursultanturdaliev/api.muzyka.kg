<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/18/17
 * Time: 1:24 AM
 */

namespace LyricsBundle\Tests\Entity;


use LyricsBundle\Entity\Artist;
use LyricsBundle\Entity\Song;
use PHPUnit\Framework\TestCase;

class SongTest extends TestCase
{
    /** @var  Song */
    private $song;

    private $artist;

    private $date;

    protected function setUp()
    {
        $this->date = new \DateTime('now');
        $this->artist = new Artist();
        $this->song = new Song();
        $this->song->setName('Kyrgyzstan')
            ->setContent('Content')
            ->setArtist($this->artist)
            ->setCreatedAt($this->date)
            ->setUpdatedAt($this->date);
    }

    public function testSong()
    {
        $this->assertEquals($this->song->getName(), 'Kyrgyzstan');
        $this->assertEquals($this->song->getContent(), 'Content');
        $this->assertEquals($this->song->getArtist(), $this->artist);
        $this->assertEquals($this->song->getCreatedAt(), $this->date);
        $this->assertEquals($this->song->getUpdatedAt(), $this->date);
    }
}