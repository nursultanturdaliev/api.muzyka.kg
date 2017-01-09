<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/9/17
 * Time: 9:57 AM
 */

namespace AppBundle\Tests\Api\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

abstract class AbstractBaseTestCase extends WebTestCase
{
    protected function checkJSONResponse(Client $client)
    {
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        ));
        $this->assertNotEmpty($client->getResponse()->getContent());
    }
}