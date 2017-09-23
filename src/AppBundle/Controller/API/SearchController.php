<?php
/**
 * Created by PhpStorm.
 * User: sheki
 * Date: 9/20/17
 * Time: 3:08 PM
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
 * @Route("/api/search")
 */

class SearchController extends ApiController
{

    /**
     * @Route("/{text}", name="app_api_search")
     * @Method("GET")
     * @ApiDoc(
     *     section="Search",
     *     resource=true,
     *     description="Gets result "
     * )
     * @param $text string
     *
     * @return Response
     */
    public function resultsAction($text)
    {
        $artists = $this->getDoctrine()->getRepository('AppBundle:Artist')->search($text);

        $songs = $this->getDoctrine()->getRepository('AppBundle:Song')->searchByAllProperties($text);

        $formattedSongs = $this->get('app_formatter.song')->format($songs);

        return $this->prepareJsonResponse(array(
            'artists' => $artists,
            'songs' => $formattedSongs
        ));
    }

}