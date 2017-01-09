<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/9/17
 * Time: 3:16 AM
 */

namespace AppBundle\Tests\Api\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GenreControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/genre/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testArtistSongsAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/genre/5/songs');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }
}