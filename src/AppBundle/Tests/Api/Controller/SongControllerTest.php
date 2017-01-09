<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/9/17
 * Time: 3:32 AM
 */

namespace AppBundle\Tests\Api\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SongControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/song/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testInfoAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/song/info');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testAllByOffsetAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/song/100/100');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
        $this->assertNotEmpty($client->getResponse()->getContent());
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

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
    }

    public function testTopAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/song/top/10');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
    }
}