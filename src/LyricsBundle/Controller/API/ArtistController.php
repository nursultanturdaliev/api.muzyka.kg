<?php

namespace LyricsBundle\Controller\API;

use AppBundle\Controller\API\ApiController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/lyrics/artist")
 * Class ArtistController
 * @package LyricsBundle\Controller
 */
class ArtistController extends ApiController
{
    /**
     * @Route("/{offset}/{limit}/", requirements={"offset"="\d+", "limit"="\d+"})
     * @Method("GET")
     * @ApiDoc(
     *     section="Lyrics",
     *     resource=true,
     *     description="Get artists with all lyrics offset by {offset} and limit by {limit}",
     *     requirements={
     *          {"name"="offset", "datatype"="integer", "requirement"="\d+", "description"="Offset"},
     *          {"name"="offset", "datatype"="integer", "requirement"="\d+", "description"="Limit"}
     *    }
     * )
     * @param $offset int
     * @param $limit int
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($offset, $limit)
    {
        $queryBuilder = $this->get('doctrine.orm.entity_manager')
            ->getRepository('LyricsBundle:Artist')
            ->createQueryBuilder('artist');
        $queryBuilder->setMaxResults($limit);
        $queryBuilder->setFirstResult($offset);
        $artists = $queryBuilder->getQuery()->execute();
        return $this->prepareJsonResponse($artists);
    }

    /**
     * @Route("/info")
     * @Method("GET")
     * @ApiDoc(
     *     section="Lyrics",
     *     resource=true,
     *     description="Get lyrics Info"
     * )
     */
    public function infoAction()
    {
        $info = $this->getDoctrine()->getRepository('LyricsBundle:Artist')->getInfo();
        return new JsonResponse($info);
    }
}
