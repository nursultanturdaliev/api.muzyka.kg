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
use AppBundle\Formatter\HistoryFormatter;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
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

		$formattedHistory = HistoryFormatter::format($user->getHistories());

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
	public function startAction(Song $song)
	{
		$em = $this->get('doctrine.orm.default_entity_manager');

		$historyId = $this->get('session')->get(History::SESSION_ID, null);
		if ($historyId) {
			$this->get('session')->remove(History::SESSION_ID);
		}

		$history = new History();
		$history->setSong($song);
		$history->setUser($this->getUser());
		$history->setStartedAt(new \DateTime('now'));

		$em->persist($history);
		$em->flush();

		$formattedHistory = HistoryFormatter::format($history);

		$this->get('session')->set(History::SESSION_ID, $history->getId());

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
		$em = $this->get('doctrine.orm.default_entity_manager');

		$historyId = $this->get('session')->get(History::SESSION_ID);

		if (!$historyId) {
			return new JsonResponse([], Response::HTTP_NOT_FOUND);
		}

		$history = $em->getRepository('AppBundle:History')->find(intval($historyId));

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

		$this->get('session')->remove(History::SESSION_ID);

		return new JsonResponse();
	}
}