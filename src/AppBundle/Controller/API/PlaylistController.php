<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 9/26/17
 * Time: 9:40 PM
 */

namespace AppBundle\Controller\API;

use AppBundle\Entity\Playlist;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/playlist")
 */
class PlaylistController extends ApiController
{
	const PLAYLIST_PER_PAGE = 50;
	/**
	 * @Route("/page/{page}", requirements={"page":"\d+"})
	 * @Method("GET")
	 * @param $page integer
	 *
	 * @return Response
	 */
	public function indexAction($page)
	{
		$playlists = $this->getDoctrine()->getRepository('AppBundle:Playlist')
						  ->findBy([], ['name' => 'ASC'], self::PLAYLIST_PER_PAGE, $page-1);

		$playlists = $this->get('app_formatter.playlist')->format($playlists);

		return $this->prepareJsonResponse($playlists);
	}

	/**
	 * @Route("/{id}", requirements={"id"="\d+"}, options={"expose"=true})
	 * @ParamConverter("artist", class="AppBundle:Playlist")
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Playlist",
	 *     description="Get playlist by id",
	 *     requirements={{"name"="id", "requirement"="\d+", "description"="Playlist ID","required"=true,
	 *     "dataType"="integer"}}
	 * )
	 *
	 * @param Playlist $playlist
	 *
	 * @return JsonResponse
	 */
	public function getAction(Playlist $playlist)
	{
		$playlistFormatted = $this->get('app_formatter.playlist')->format($playlist);
		return $this->prepareJsonResponse($playlistFormatted);
	}
}