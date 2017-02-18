<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/9/17
 * Time: 9:57 AM
 */

namespace AppBundle\Test;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 *
 * @codeCoverageIgnore
 *
 * Class AbstractBaseTestCase
 * @package AppBundle\Test
 */
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

    protected static function resetDb()
    {
        shell_exec('php app/console doctrine:database:drop --force');
        shell_exec('php app/console doctrine:database:create --quiet');
        shell_exec('php app/console doctrine:schema:update --force --quiet');
        shell_exec('php app/console doctrine:fixtures:load --no-interaction --quiet');
    }

    /**
     * @param $client
     * @return mixed
     */
    protected function getArrayResponse(\Symfony\Component\BrowserKit\Client $client)
    {
        return \GuzzleHttp\json_decode($client->getResponse()->getContent(), true);
    }
}