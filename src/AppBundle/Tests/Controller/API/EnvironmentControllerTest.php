<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 3/13/17
 * Time: 7:05 PM
 */

namespace AppBundle\Tests\Controller\API;


use AppBundle\Test\AbstractBaseTestCase;

class EnvironmentControllerTest extends AbstractBaseTestCase
{
    public function testVariablesAction(){
        $client = $this->createClient();
        $client->request('get','/api/environment/variables');
        $response = \GuzzleHttp\json_decode($client->getResponse()->getContent(),true);

        $this->checkJSONResponse($client);
        $this->assertArrayHasKey('BASE_URL', $response);
        $this->assertArrayHasKey('BASE_URL_API',$response);
    }
}