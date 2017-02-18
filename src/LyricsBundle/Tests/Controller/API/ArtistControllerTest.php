<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 2/18/17
 * Time: 12:52 AM
 */

namespace LyricsBundle\Tests\Controller\API;


use AppBundle\Test\AbstractBaseTestCase;

class ArtistControllerTest extends AbstractBaseTestCase
{
    public static function setUpBeforeClass()
    {
        self::resetDb();
    }

    public function testIndexAction()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $client->request('GET', '/api/lyrics/artist/0/1');

        $response = $this->getArrayResponse($client);

        $this->assertEquals(sizeof($response), 1);
        $this->assertEquals($response[0]['id'], 1);
        $this->assertEquals($response[0]['name'], 'Nursultan');

        $this->assertEquals(sizeof($response[0]['songs']), 1);
        $this->assertEquals($response[0]['songs'][0]['name'], 'Kyrgyzstan');
        $this->assertEquals($response[0]['songs'][0]['content'], 'Content');
    }

    public function testInfoAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/lyrics/artist/info');

        $this->assertEquals('{"count":1}', $client->getResponse()->getContent());
    }
}