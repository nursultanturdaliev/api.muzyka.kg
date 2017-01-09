<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/9/17
 * Time: 2:13 AM
 */

namespace AppBundle\Tests\Api\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArtistControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/artist/');

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
        $client->request('GET', '/api/artist/info');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
        $this->assertNotEmpty($client->getResponse()->getContent());

    }

    public function testByOffsetAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/artist/0/100');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testGetAction()
    {
        $client = static::createClient();
        $client->request('GET','/api/artist/2360');
        $this->assertSame(200,$client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testArtistSongsAction()
    {
        $client = static::createClient();
        $client->request('GET','/api/artist/2360/songs');
        $this->assertSame(200,$client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }
}