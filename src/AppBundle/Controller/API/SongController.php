<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/3/17
 * Time: 5:08 PM
 */

namespace AppBundle\Controller\API;

use AppBundle\Entity\Song;
use AppBundle\Repository\SongRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/song")
 */
class SongController extends ApiController
{
	const MAXIMUM_SONG_RESPONSE = 100;

	/**
	 * @Route("/search/{text}", name="app_api_song_search", options={"expose"=true})
	 *
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Search songs"
	 * )
	 *
	 * @param $text
	 *
	 * @return Response
	 */
	public function searchAction($text)
	{
		/** @var SongRepository $searchRepository */
		$searchRepository = $this->getDoctrine()->getRepository('AppBundle:Song');
		$songs            = $searchRepository->search($text);
		$formattedSongs   = $this->get('app_formatter.song')->format($songs);;
		return $this->prepareJsonResponse($formattedSongs);
	}

	/**
	 * @Route("/random/{max}", name="app_api_song_random", options={"expose"=true}, requirements={"max"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Random Songs",
	 *     requirements={{"name"="max", "dataType"="integer", "requirement"="\d+", "description"="Maximum result"}}
	 * )
	 * @param Request $request
	 * @param $max int
	 *
	 * @return Response
	 */
	public function randomAction(Request $request, $max)
	{
		$artistId = $request->query->get('artistId');
		$songs    = $this->get('doctrine.orm.default_entity_manager')->getRepository('AppBundle:Song')
						 ->getRandomSongs($max, $artistId);

		return $this->prepareJsonResponse($this->get('app_formatter.song')->format($songs));
	}

	/**
	 * @Route("/topdownloads/{max}", name="app_api_song_top_downloads", options={"expose"=true},
	 *                               requirements={"max"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Returns Top Downloads",
	 *     requirements={{"name"="max", "dataType"="integer", "requirement"="\d+", "description"="Maximum number of
     *     songs"}}
     * )
	 * @param $max
	 *
	 * @return Response
	 */
	public function topDownloadsAction($max)
	{
		$songs = $this->get('doctrine.orm.default_entity_manager')->getRepository('AppBundle:Song')
					  ->topDownloads($max);
		return $this->prepareJsonResponse($songs);
	}

	/**
	 * @Route("/status/new",name="app_api_song_new_release", options={"expose"=true},
	 *                                                              requirements={"limit"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Returns new releases",
	 *     requirements={{"name"="max", "dataType"="integer", "requirement"="\d+", "description"="Maximum number of
     *     songs"}}
     * )
	 * @param $limit
	 *
	 * @return Response
	 */
	public function statusNewAction()
	{

		$songs = $this->get('doctrine.orm.default_entity_manager')
					  ->getRepository('AppBundle:Song')
					  ->newReleases();

		$formattedSongs = $this->get('app_formatter.song')->format($songs);
		return $this->prepareJsonResponse($formattedSongs);
	}

	/**
	 * @Route("/info", name="app_api_song_info")
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Statistical information about songs"
	 * )
	 */
	public function infoAction()
	{
		$info = $this->getDoctrine()->getRepository('AppBundle:Song')->getInfo();
		return new JsonResponse($info);
	}

	/**
	 * @Route("/page/{page}/", name="app_api_song_pagination", requirements={"page"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     section="Song",
	 *     resource=true,
	 *     description="Gets songs by {offset} and {limit}",
	 *     requirements={
	 *          {"name"="page", "dataType"="integer", "requirement"="\d+", "description"="Page"},
	 *     }
	 * )
	 * @param $page
	 *
	 * @return Response
	 */
	public function pageAction($page = 1)
	{
		$songs          = $this->getDoctrine()->getRepository('AppBundle:Song')
							   ->findBy(array(), array('title' => 'ASC'), self::MAXIMUM_SONG_RESPONSE, self::MAXIMUM_SONG_RESPONSE * abs($page - 1));
		$formattedSongs = $this->get('app_formatter.song')->format($songs);
		return $this->prepareJsonResponse($formattedSongs);
	}

	/**
	 * @Route("/{uuid}", name="app_api_song_get")
	 * @Method("GET")
	 * @param $uuid
	 *
	 * @return JsonResponse
	 * @internal param Song $song
	 *
	 * @ApiDoc(
	 *     section="Song",
	 *     resource=true,
	 *     description="Get song by uuid",
	 *     requirements={
	 *          {"name"="uuid", "dataType"="string", "requirement"="\w+", "required"=true, "description"="Universal
     *          unique identity"}
     *     }
	 * )
	 */
	public function getAction($uuid)
	{
		$song = $this->getDoctrine()->getRepository('AppBundle:Song')->findOneByUuid($uuid);
		return $this->prepareJsonResponse($song);
	}

	/**
	 * @Route("/top/{page}", name="app_api_song_top", options={"expose"=true}, requirements={"page"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Get songs by ranking, using offset",
	 *     requirements={{"name"="page", "dataType"="integer", "required"=true, "requirement"="\d+", "description"="Limit"}}
	 * )
	 *
	 * @return JsonResponse
	 *
	 * @param $page
	 *
	 * @return Response
	 */
	public function topAction($page)
	{
		$songs = $this->getDoctrine()
					  ->getRepository('AppBundle:Song')
					  ->top()
					  ->setMaxResults(20)
					  ->setFirstResult(abs($page - 1) * 10)
					  ->getResult();

		$formattedSongs = $this->get('app_formatter.song')->formatTop($songs);
		return $this->prepareJsonResponse($formattedSongs);
	}
}