<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/17/17
 * Time: 10:29 PM
 */

namespace AppBundle\Tests\Controller;


use AppBundle\Test\AbstractBaseTestCase;

class SongControllerTest extends AbstractBaseTestCase
{
	public static function setUpBeforeClass()
	{
		self::resetDb();
	}


	public function testStreamAction()
	{
		$client = static::createClient();
		$client->request('GET', '/song/c25a07b8-15e2-481b-a9be-8aa962d811e4/stream');

		$this->assertSame(200, $client->getResponse()->getStatusCode());
		$this->assertTrue($client->getResponse()->headers->contains(
			'Content-Type',
			'application/octet-stream'
		));
	}
}