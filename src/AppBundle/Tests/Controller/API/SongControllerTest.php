<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/9/17
 * Time: 3:32 AM
 */

namespace AppBundle\Tests\Controller\API;

use AppBundle\Test\AbstractBaseTestCase;

class SongControllerTest extends AbstractBaseTestCase
{
	public static function setUpBeforeClass()
	{
		self::resetDb();
	}


	public function testIndexAction()
	{
		$client = static::createClient();
		$client->request('GET', '/api/song/');

		$this->checkJSONResponse($client);
		/** @var array $content */
		$content = json_decode($client->getResponse()->getContent(), true);

		$this->assertEquals(sizeof($content), 1);
		$this->assertEquals($content[0]['title'], 'Kyrgyzstan');
		$this->assertEquals($content[0]['duration'], '04:00');
		$this->assertEquals($content[0]['downloadable'], 1);
		$this->assertEquals($content[0]['countDownload'], 0);
		$this->assertEquals($content[0]['likes'], 1000);
		$this->assertEquals($content[0]['lyrics'], 'Lyrics');
	}

	public function testInfoAction()
	{
		$client = static::createClient();
		$client->request('GET', '/api/song/info');

		$this->checkJSONResponse($client);
		$this->assertEquals('{"count":4}', $client->getResponse()->getContent());
	}

	public function testAllByOffsetAction()
	{
		$client = static::createClient();
		$client->request('GET', '/api/song/0/1');

		$this->checkJSONResponse($client);
		$response = $this->getArrayResponse($client);

		$this->assertEquals(sizeof($response), 1);
		$this->assertEquals($response[0]['title'], 'Kyrgyzstan');
	}

	public function testGetAction()
	{
		$client = static::createClient();
		$client->request('GET', '/api/song/c25a07b8-15e2-481b-a9be-8aa962d811e4');

		$response = $this->getArrayResponse($client);

		$this->assertEquals($response['artist_id'], 1);
		$this->assertEquals($response['duration'], '04:00');
		$this->assertEquals($response['count_play'], 1000);
		$this->assertEquals($response['old_url'], '');
		$this->assertEquals($response['title'], 'Kyrgyzstan');
		$this->assertEquals($response['uuid'], 'c25a07b8-15e2-481b-a9be-8aa962d811e4');

		$this->checkJSONResponse($client);
	}

	public function testTopAction()
	{
		$client = static::createClient();
		$client->request('GET', '/api/song/top/10');

		$this->checkJSONResponse($client);
	}

	public function testIncreasePlayCountAction()
	{
		$client = static::createClient();
		$client->request('GET', '/api/song/1/increase_count_play');
		$response = $this->getArrayResponse($client);
		$this->assertEquals($response['count_play'], 1001);
	}

	public function testRandomAction()
	{
		$client = static::createClient();
		$client->request('GET', '/api/song/random/2');
		$response = $this->getArrayResponse($client);
		$this->checkJSONResponse($client);
		$this->assertEquals(2, sizeof($response));
	}

	public function testTopDownloadsAction()
	{
		$client = static::createClient();
		$client->request('GET', '/api/song/topdownloads/2');

		$response = $this->getArrayResponse($client);
		$this->checkJSONResponse($client);
		$this->assertEquals(4, $response['count_download']);
		$this->assertEquals(2, sizeof($response));
	}
}