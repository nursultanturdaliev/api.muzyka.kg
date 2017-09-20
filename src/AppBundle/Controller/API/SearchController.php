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
     * @Route("/{text}", name="app_api_search", options={"expose"=true})
     * @Method("GET")
     * @ApiDoc(
     *     resource=true,
     *     section="Search",
     *     description="Get result Songs and Artists",
     *     requirements={{"text"="sting",  "description"="text","required"=true,
     *     "dataType"="string"}}
     * )
     *
     * @return Response
     */
    public function resultsAction($text)
    {
        $artists = $this->getDoctrine()->getRepository('AppBundle:Artist')
            ->createQueryBuilder('artist')
            ->select('artist.id')
            ->addSelect('artist.name')
            ->addSelect('artist.lastname')
            ->where('lower(artist.name) LIKE lower(:text)')
            ->orWhere('lower(artist.lastname) LIKE lower(:text)')
            ->setParameter('text', '%' . $text . '%')
            ->getQuery()
            ->execute(null, AbstractQuery::HYDRATE_SCALAR);

        $songs = $this->getDoctrine()->getRepository('AppBundle:Song')
            ->createQueryBuilder('song')
            ->select('song.id')
            ->addSelect('song.title')
            ->addSelect('song.uuid')
            ->addSelect('song.duration')
            ->join('song.artist', 'artist')
            ->addSelect('artist.id artist_id')
            ->where('lower(song.title) LIKE lower(:text)')
            ->setParameter('text', '%' . $text . '%')
            ->getQuery()
            ->execute(null, AbstractQuery::HYDRATE_SCALAR);

        return $this->prepareJsonResponse(array(
            'artists' => $artists,
            'songs' => $songs
        ));
    }

}