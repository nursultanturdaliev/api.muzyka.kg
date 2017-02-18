<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/9/17
 * Time: 2:13 AM
 */

namespace AppBundle\Tests\Controller\API;

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
        $client->request('GET', '/api/artist/');

        $this->checkJSONResponse($client);
    }

    public function testInfoAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/artist/info');

        $this->checkJSONResponse($client);

    }

    public function testByOffsetAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/artist/0/100');
        $this->checkJSONResponse($client);
    }

    public function testGetAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/artist/1');
        $this->checkJSONResponse($client);
    }

    public function testArtistSongsAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/artist/1/songs');
        $this->checkJSONResponse($client);
    }
}