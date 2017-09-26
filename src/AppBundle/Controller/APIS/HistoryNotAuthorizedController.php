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
 * @Route("/aapis/history")
 */
class HistoryNotAuthorizedController extends ApiController
{


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
        $session = $request->cookies->get('PHPSESSID');
        $clientIp = $request->getClientIp();
        $redisKey = $session.':'.$song->getUuid();
        $historyId = $redis->get($redisKey);

		if ($historyId) {
			$history = $em->getRepository('AppBundle:History')->find($historyId);
			$em->remove($history);
			$em->flush();
			$redis->del($redisKey);
		}

		$history = new History();
		$history->setSong($song);
		$history->setClientIp($clientIp);
		$history->setStartedAt(new \DateTime('now'));
		$em->persist($history);
		$em->flush();

		$formattedHistory = $this->get('app_formatter.history')->format($history);
        $redis->set($redisKey, $history->getId());

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
	public function stopAction(Request $request, Song $song)
	{
		$this->get('logger')->addDebug('STOP', ['uuid' => $song->getUuid()]);
		$em = $this->get('doctrine.orm.default_entity_manager');
		$redis     = $this->container->get('snc_redis.default');
        $session = $request->cookies->get('PHPSESSID');
        $redisKey = $session.':'.$song->getUuid();
        $historyId = $redis->get($redisKey);
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

		$redis->del($redisKey);

		return new JsonResponse();
	}
}