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
	/**
	 * @Route("/",name="app_api_song_index")
	 * @Method("GET")
	 * @ApiDoc(
	 *     section="Song",
	 *     resource=true,
	 *     description="Get all songs"
	 * )
	 */
	public function indexAction()
	{
		$songsAsArray = $this->getDoctrine()->getRepository('AppBundle:Song')->findAllQuery()->getQuery()->getArrayResult();
		return new JsonResponse($songsAsArray);
	}

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
		return $this->prepareJsonResponse($songs);
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
		return $this->prepareJsonResponse($songs);
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
	 * @Route("/newreleases/{max}",name="app_api_song_new_release", options={"expose"=true},
	 *                                                              requirements={"max"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Returns new releases",
	 *     requirements={{"name"="max", "dataType"="integer", "requirement"="\d+", "description"="Maximum number of
     *     songs"}}
     * )
	 * @param $max
	 *
	 * @return Response
	 */
	public function newReleaseAction($max)
	{
		$songs = $this->get('doctrine.orm.default_entity_manager')
					  ->getRepository('AppBundle:Song')
					  ->newReleases($max);
		return $this->prepareJsonResponse($songs);
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
	 * @Route("/{offset}/{limit}", name="app_api_song_all_by_offset", requirements={"offset"="\d+", "limit"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     section="Song",
	 *     resource=true,
	 *     description="Gets songs by {offset} and {limit}",
	 *     requirements={
	 *          {"name"="offset", "dataType"="integer", "requirement"="\d+", "description"="Offset"},
	 *          {"name"="limit", "dataType"="integer", "requirement"="\d+", "description"="Limit"}
	 *     }
	 * )
	 * @param $offset
	 * @param $limit
	 *
	 * @return Response
	 */
	public function allByOffsetAction($offset, $limit)
	{
		$songs = $this->getDoctrine()->getRepository('AppBundle:Song')
					  ->findBy(array(), array(), $limit, $offset);
		return $this->prepareJsonResponse($songs);
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
	 * @Route("/top/{offset}/{limit}", name="app_api_song_top", options={"expose"=true}, requirements={"offset"="\d+",
	 *                                 "limit"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Get songs by ranking, using offset",
	 *     requirements={{"name"="offset", "dataType"="integer", "required"=true, "requirement"="\d+",
	 *     "description"="Offset"},
	 *     {"name"="limit", "dataType"="integer", "required"=true, "requirement"="\d+", "description"="Limit"}}
	 * )
	 * @param $offset
	 * @param $limit
	 *
	 * @return JsonResponse
	 */
	public function topAction($offset, $limit)
	{
		$songs = $this->getDoctrine()
					  ->getRepository('AppBundle:Song')
					  ->createQueryBuilder('song')
					  ->setMaxResults($limit)
					  ->setFirstResult($offset)
					  ->orderBy('song.countPlay')
					  ->getQuery()
					  ->execute();
		return $this->prepareJsonResponse($songs);
	}

	/**
	 * @Route("/{id}/increase_count_play", name="app_api_song_increase", options={"expose"=true},
	 *                                     requirements={"id"="\d+"})
	 * @Method({"PUT", "OPTIONS"})
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Increase song play count",
	 *     requirements={{"name"="id", "dataType"="integer", "required"=true, "requirement"="\d+", "description"="Song
     *     Id"}}
     * )
	 * @param Song $song
	 *
	 * @return Response
	 */
	public function increasePlayCountAction(Song $song)
	{
		if ($this->get('request')->isMethod('PUT')) {
			$song->setCountPlay($song->getCountPlay() + 1);

			$em = $this->get('doctrine.orm.default_entity_manager');
			$em->persist($song);
			$em->flush();
		}
		return $this->prepareJsonResponse($song);
	}
}