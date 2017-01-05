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
     */
    public function indexAction()
    {
        $artists = $this->getDoctrine()->getRepository('AppBundle:Artist')
            ->createQueryBuilder('a')
            ->getQuery()->getArrayResult();
        return new JsonResponse($artists);
    }

    /**
     * @Route("/{id}", name="app_api_artist_get")
     * @ParamConverter("artist", class="AppBundle:Artist")
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