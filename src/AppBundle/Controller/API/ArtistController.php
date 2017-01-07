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
class ArtistController extends Controller
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
        return new Response(
            $this->get('jms_serializer')->serialize($artists,'json', 200, array(
                'ContentType'=>'application/json; charset=UTF-8'
            ))
        );
    }

    /**
     * @Route("/{id}", name="app_api_artist_get", requirements={"id"="\d+"}, options={"expose"=true})
     * @ParamConverter("artist", class="AppBundle:Artist")
     * @Method("GET")
     * @ApiDoc(
     *     resource=true,
     *     section="Artist",
     *     description="Get artist by id",
     *     requirements={{"name"="id", "requirement"="\d+", "description"="Artist ID","required"="true", "dataType"="integer"}}
     * )
     * @param Artist $artist
     * @return JsonResponse
     */
    public function getAction(Artist $artist)
    {
        return new Response($this->get('jms_serializer')->serialize($artist, 'json'), 200, array(
            'Content-Type' => 'application/json;  charset=UTF-8'
        ));
    }
}