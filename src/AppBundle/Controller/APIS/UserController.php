<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 8/12/17
 * Time: 10:46 PM
 */

namespace AppBundle\Controller\APIS;

use AppBundle\Controller\API\ApiController;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/apis/user")
 */
class UserController extends ApiController
{
	/**
	 * @Route("/update")
	 * @Method("PUT")
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @internal param Request $request
	 */
	function updateAction(Request $request)
	{
		$content = \GuzzleHttp\json_decode($request->getContent(), false);

		/** TODO: Use json schema validation */
		if (!$content &
			(!$content->first_name || !$content->last_name)
		) {
			return new JsonResponse(null, Response::HTTP_PRECONDITION_FAILED);
		}

		/** @var User $user */
		$user = $this->getUser();
		$user->setFirstName($content->first_name);
		$user->setLastName($content->last_name);

		$this->get('doctrine.orm.default_entity_manager')->flush($user);

		$formattedUser = $this->get('app_formatter.user')->format($user);
		return $this->prepareJsonResponse($formattedUser);
	}

	/**
	 * @Route("/")
	 * @Method("GET")
	 */
	function getAction()
	{
		$formattedUser = $this->get('app_formatter.user')->format($this->getUser());
		return $this->prepareJsonResponse($formattedUser);
	}
}