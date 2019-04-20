<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/8/17
 * Time: 8:42 PM
 */

namespace AppBundle\Controller\API;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
	protected function prepareJsonResponse($object, $cacheMaxAge = 3600)
	{
		 $response = new Response($this->get('jms_serializer')->serialize($object, 'json'), 200, array(
			'Content-Type' => 'application/json',
            ''
		));

		 $response->setMaxAge($cacheMaxAge);
		 return $response;
	}


}