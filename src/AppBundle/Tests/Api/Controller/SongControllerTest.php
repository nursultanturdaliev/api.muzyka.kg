<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/9/17
 * Time: 3:32 AM
 */

namespace AppBundle\Tests\Api\Controller;

class SongControllerTest extends AbstractBaseTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/song/');

        $this->checkJSONResponse($client);
    }

    public function testInfoAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/song/info');

        $this->checkJSONResponse($client);
    }

    public function testAllByOffsetAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/song/100/100');

        $this->checkJSONResponse($client);
    }

    public function testStreamAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/song/d2d852bc-939b-4dfa-86c2-d8232a0dae62/stream');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/octet-stream'
        ));
    }

    public function testGetAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/song/d2d852bc-939b-4dfa-86c2-d8232a0dae62');

        $this->checkJSONResponse($client);
    }

    public function testTopAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/song/top/10');

        $this->checkJSONResponse($client);
    }
}