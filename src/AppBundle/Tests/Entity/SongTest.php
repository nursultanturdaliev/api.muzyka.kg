<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/18/17
 * Time: 12:00 AM
 */

namespace AppBundle\Tests\Entity;


use AppBundle\Entity\Artist;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Playlist;
use AppBundle\Entity\Song;
use PHPUnit\Framework\TestCase;

class SongTest extends TestCase
{
    /** @var  Song */
    private $song;

    private $createdAt;

    protected function setUp()
    {
        $this->createdAt = new \DateTime('now');
        $this->song = new Song();
        $this->song->setTitle('Kyrgyzstan')
            ->setDuration('04:00')
            ->setCountPlay(100)
            ->setLikes(100)
            ->setLyrics('Lyrics')
            ->setCreatedAt($this->createdAt)
            ->setUpdatedAt($this->createdAt)
            ->setDownloadable(true)
            ->setCountDownload(100)
            ->setDownloadable(false)
            ->setPublished(true)
            ->setPublishedAt($this->createdAt)
            ->setUuid('uuid');
    }

    public function testSong()
    {
        $this->assertEquals($this->song->getTitle(), 'Kyrgyzstan');
        $this->assertEquals($this->song->getDuration(), '04:00');
        $this->assertFalse($this->song->isDownloadable());
        $this->assertFalse($this->song->getDownloadable());
        $this->assertEquals($this->song->getCountPlay(), 100);
        $this->assertEquals($this->song->getLikes(), 100);
        $this->assertEquals($this->song->getLyrics(), 'Lyrics');
        $this->assertEquals($this->song->getCreatedAt(), $this->createdAt);
        $this->assertEquals($this->song->getUpdatedAt(), $this->createdAt);
        $this->assertEquals($this->song->getCountDownload(), 100);
        $this->assertEquals($this->song->getUuid(), 'uuid');
        $this->assertTrue($this->song->getPublished());
        $this->assertEquals($this->song->getPublishedAt(), $this->createdAt);
        $this->assertFalse($this->song->isDownloadable());
    }

    public function testSongArtist()
    {
        $artist = new Artist();
        $this->song->setArtist($artist);

        $this->assertEquals($this->song->getArtist(), $artist);
    }

    public function testAddGenre()
    {
        $genre = new Genre();

        $this->assertEquals($this->song->getGenres()->count(), 0);

        $this->song->addGenre($genre);

        $this->assertTrue($this->song->getGenres()->contains($genre));

    }

    public function testRemove()
    {
        $genre = new Genre();

        $this->song->addGenre($genre);

        $this->assertTrue($this->song->getGenres()->contains($genre));

        $this->song->removeGenre($genre);

        $this->assertFalse($this->song->getGenres()->contains($genre));
    }

    public function testRemovePlaylist()
    {
        $playlist = new Playlist();
        $this->song->addPlaylist($playlist);

        $this->assertTrue($this->song->getPlaylists()->contains($playlist));

        $this->song->removePlaylist($playlist);

        $this->assertFalse($this->song->getPlaylists()->contains($playlist));
    }

}