<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/5/17
 * Time: 12:17 AM
 */

namespace AppBundle\Controller\API;

use AppBundle\Entity\Artist;
use AppBundle\Formatter\ArtistFormatter;
use Doctrine\ORM\AbstractQuery;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/artist")
 */
class ArtistController extends ApiController
{
	const ARTIST_PER_RESPONSE_LIMIT = 100;

	/**
	 * @Route("/page/{page}", name="app_api_artist_index", requirements={"page":"\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Artist",
	 *     description="Get all artists"
	 * )
	 * @param $page integer
	 *
	 * @return Response
	 */
	public function indexAction($page)
	{
		$artists = $this->getDoctrine()->getRepository('AppBundle:Artist')->createQueryBuilder('artist')
						->setMaxResults(self::ARTIST_PER_RESPONSE_LIMIT)
						->setFirstResult(abs($page - 1) * self::ARTIST_PER_RESPONSE_LIMIT)
						->getQuery()->execute();

		$artists = ArtistFormatter::format($artists);
		return $this->prepareJsonResponse($artists);
	}

	/**
	 * @Route("/info", name="app_api_artist_info")
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Artist",
	 *     description="Statistical information about artists"
	 * )
	 */
	public function infoAction()
	{
		$info = $this->getDoctrine()->getRepository('AppBundle:Artist')->getInfo();
		return new JsonResponse($info);
	}

	/**
	 * @Route("/{offset}/{limit}", name="app_api_artist_by_offset", requirements={"offset"="\d+", "limit"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     section="Song",
	 *     resource=true,
	 *     description="Gets artists by {offset} and {limit}",
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
	public function byOffsetAction($offset, $limit)
	{
		$artists = $this->getDoctrine()->getRepository('AppBundle:Artist')
						->createQueryBuilder('artist')
						->select('artist.id')
						->addSelect('artist.instagram')
						->addSelect('artist.name')
						->addSelect('artist.lastname')
						->setFirstResult($offset)
						->setMaxResults($limit)
						->getQuery()
						->execute(null, AbstractQuery::HYDRATE_SCALAR);
		return $this->prepareJsonResponse($artists);
	}


	/**
	 * @Route("/{id}", name="app_api_artist_get", requirements={"id"="\d+"}, options={"expose"=true})
	 * @ParamConverter("artist", class="AppBundle:Artist")
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Artist",
	 *     description="Get artist by id",
	 *     requirements={{"name"="id", "requirement"="\d+", "description"="Artist ID","required"=true,
	 *     "dataType"="integer"}}
	 * )
	 * @param Artist $artist
	 *
	 * @return JsonResponse
	 */
	public function getAction(Artist $artist)
	{
		return $this->prepareJsonResponse(ArtistFormatter::format($artist));
	}

	/**
	 * @Route("/{id}/songs", name="app_api_artist_songs", requirements={"id"="\d+"}, options={"expose"=true})
	 * @ParamConverter("artist", class="AppBundle:Artist")
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Artist",
	 *     description="Get Songs of an artist",
	 *     requirements={{"name"="id", "requirement"="\d+", "description"="Artist ID","required"=true,
	 *     "dataType"="integer"}}
	 * )
	 * @param Artist $artist
	 *
	 * @return Response
	 */
	public function artistSongsAction(Artist $artist)
	{
		return $this->prepareJsonResponse($artist->getSongs());
	}
}