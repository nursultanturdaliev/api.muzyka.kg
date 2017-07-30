<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/17/17
 * Time: 11:02 PM
 */

namespace AppBundle\Tests\Entity;


use AppBundle\Entity\Playlist;
use AppBundle\Entity\Song;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class PlaylistTest extends TestCase
{
	/** @var  Playlist */
	private $playlist;

	private static $createdAt;

	public static function setUpBeforeClass()
	{
		self::$createdAt = new \DateTime('now');
	}


	protected function setUp()
	{
		$this->playlist = new Playlist();
		$this->playlist->setName('One');
		$this->playlist->setCreatedAt(self::$createdAt);
		$this->playlist->setUpdatedAt(self::$createdAt);
		$song = new Song();
		$this->playlist->addSong($song);
	}

	public function testPlaylist()
	{
		$this->assertEquals($this->playlist->getName(), 'One');
		$this->assertEquals($this->playlist->getCreatedAt(), self::$createdAt);
		$this->assertEquals($this->playlist->getUpdatedAt(), self::$createdAt);
	}

	public function testAddSong()
	{
		$this->assertEquals($this->playlist->getSongs()->count(), 1);

		$song = new Song();
		$this->playlist->addSong($song);

		$this->assertEquals($this->playlist->getSongs()->count(), 2);

		$this->assertTrue($this->playlist->getSongs()->contains($song));
	}

	public function testRemoveSong()
	{
		$this->assertEquals($this->playlist->getSongs()->count(), 1);

		$song = new Song();
		$this->playlist->addSong($song);

		$this->assertEquals($this->playlist->getSongs()->count(), 2);

		$this->playlist->removeSong($song);

		$this->assertEquals($this->playlist->getSongs()->count(), 1);
		$this->assertFalse($this->playlist->getSongs()->contains($song));
	}

	public function testUser()
	{

		$this->assertEquals($this->playlist->getUser(), null);

		$user = new User();
		$this->playlist->setUser($user);

		$this->assertEquals($this->playlist->getUser(), $user);
	}
}