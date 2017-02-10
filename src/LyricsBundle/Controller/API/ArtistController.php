<?php

namespace LyricsBundle\Controller\API;

use AppBundle\Controller\API\ApiController;
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
     */
    public function infoAction()
    {
        $info = $this->getDoctrine()->getRepository('LyricsBundle:Artist')->getInfo();
        return new JsonResponse($info);
    }
}
