<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/9/17
 * Time: 3:16 AM
 */

namespace AppBundle\Tests\Controller\API;

use AppBundle\Test\AbstractBaseTestCase;

class GenreControllerTest extends AbstractBaseTestCase
{
	public static function setUpBeforeClass()
	{
		self::resetDb();
	}


	public function testIndexAction()
	{
		$client = static::createClient();
		$client->request('GET', '/api/genre/');

		$this->checkJSONResponse($client);
	}

	public function testArtistSongsAction()
	{
		$client = static::createClient();
		$client->request('GET', '/api/genre/5/songs');

		$this->checkJSONResponse($client);
	}
}