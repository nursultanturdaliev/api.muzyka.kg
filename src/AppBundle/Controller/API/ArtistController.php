<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/5/17
 * Time: 12:17 AM
 */

namespace AppBundle\Controller\API;

use AppBundle\Entity\Artist;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/{id}", name="app_api_artist_get", requirements={"id"="\d+"}, options={"expose"=true})
     * @ParamConverter("artist", class="AppBundle:Artist")
     * @Method("GET")
     * @ApiDoc(
     *     resource=true,
     *     section="Artist",
     *     description="Get artist by id",
     *     requirements={{"name"="id", "requirement"="\d+", "description"="Artist ID","required"=true, "dataType"="integer"}}
     * )
     * @param Artist $artist
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
     *     requirements={{"name"="id", "requirement"="\d+", "description"="Artist ID","required"=true, "dataType"="integer"}}
     * )
     * @param Artist $artist
     * @return Response
     */
    public function artistSongsAction(Artist $artist)
    {
        return $this->prepareJsonResponse($artist->getSongs());
    }
}