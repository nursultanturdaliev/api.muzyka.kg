<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/5/17
 * Time: 12:17 AM
 */

namespace AppBundle\Controller\API;

use AppBundle\Entity\Artist;
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
	/**
	 * @Route("/", name="app_api_artist_index")
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Artist",
	 *     description="Get all artists"
	 * )
	 */
	public function indexAction()
	{
		$artists = $this->getDoctrine()->getRepository('AppBundle:Artist')->findAll();
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
		return $this->prepareJsonResponse($artist);
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