<?php

namespace AppBundle\Controller\APIS;

use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/apis/playlist")
 */
class PlaylistController extends Controller
{
	/**
	 * @Route("/")
	 */
	public function indexAction()
	{
		return new JsonResponse(array(
			'hello'
		));
	}
}
