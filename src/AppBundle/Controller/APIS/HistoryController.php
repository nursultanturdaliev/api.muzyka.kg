<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 7/30/17
 * Time: 1:33 AM
 */

namespace AppBundle\Controller\APIS;


use AppBundle\Controller\API\ApiController;
use AppBundle\Entity\History;
use AppBundle\Entity\Song;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/apis/history")
 */
class HistoryController extends ApiController
{


	/**
	 * @Route("/")
	 */
	public function allAction()
	{
		/** @var User $user */
		$user = $this->getUser();

		$formattedHistory = $this->get('app_formatter.history')->format($user->getHistories());

		return $this->prepareJsonResponse($formattedHistory);
	}

	/**
	 * @Route("/start/{uuid}")
	 * @Method("POST")
	 * @ParamConverter("song", class="AppBundle:Song", options={"uuid"="uuid"})
	 * @param Song $song
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function startAction(Request $request, Song $song)
	{
		$this->get('logger')->addDebug('START', ['uuid' => $song->getUuid()]);
		$em    = $this->get('doctrine.orm.default_entity_manager');
		$redis = $this->container->get('snc_redis.default');
        $clientIp = $request->getClientIp();
		$historyId = $redis->get($song->getRedisKey($this->getUser()));
		if ($historyId) {
			$history = $em->getRepository('AppBundle:History')->find($historyId);
			$em->remove($history);
			$em->flush();
			$redis->del($song->getRedisKey($this->getUser()));
		}

		$history = new History();
		$history->setSong($song);
        $history->setClientIp($clientIp);
		$history->setUser($this->getUser());
		$history->setStartedAt(new \DateTime('now'));

		$em->persist($history);
		$em->flush();

		$formattedHistory = $this->get('app_formatter.history')->format($history);

		$redis->set($song->getRedisKey($this->getUser()), $history->getId());

		return $this->prepareJsonResponse($formattedHistory);
	}

	/**
	 * @Route("/stop/{uuid}")
	 * @Method("PUT")
	 * @ParamConverter("song", class="AppBundle:Song", options={"uuid"="uuid"})
	 * @param Song $song
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function stopAction(Song $song)
	{
		$this->get('logger')->addDebug('STOP', ['uuid' => $song->getUuid()]);
		$em = $this->get('doctrine.orm.default_entity_manager');

		$redis     = $this->container->get('snc_redis.default');
		$historyId = $redis->get($song->getRedisKey($this->getUser()));

		if (!$historyId) {
			return new JsonResponse([], Response::HTTP_NOT_FOUND);
		}

		$history = $em->getRepository('AppBundle:History')->find($historyId);

		if (!$history || !$history->getSong()->equals($song)) {
			return new JsonResponse([], Response::HTTP_NOT_FOUND);
		}
		$history->setStoppedAt(new \DateTime('now'));

		if ($history->acceptAsListened()) {
			$em->persist($history);
		} else {
			$em->remove($history);
		}
		$em->flush();

		$redis->del($song->getRedisKey($this->getUser()));

		return new JsonResponse();
	}
}