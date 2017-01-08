<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/3/17
 * Time: 5:08 PM
 */

namespace AppBundle\Controller\API;

use AppBundle\Entity\Song;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/song")
 */
class SongController extends ApiController
{
    /**
     * @Route("/",name="app_api_song_index")
     * @Method("GET")
     * @ApiDoc(
     *     section="Song",
     *     resource=true,
     *     description="Get all songs"
     * )
     */
    public function allAction()
    {
        $songsAsArray = $this->getDoctrine()->getRepository('AppBundle:Song')->findAllQuery()->getQuery()->getArrayResult();
        return new JsonResponse($songsAsArray);
    }

    /**
     * @Route("/info", name="app_api_song_info")
     * @Method("GET")
     * @ApiDoc(
     *     resource=true,
     *     section="Song",
     *     description="Statistical information about songs"
     * )
     */
    public function infoAction()
    {
        $info = $this->getDoctrine()->getRepository('AppBundle:Song')->getInfo();
        return new JsonResponse($info);
    }

    /**
     * @Route("/{offset}/{limit}", name="app_api_song_all_by_offset", requirements={"offset"="\d+", "limit"="\d+"})
     * @Method("GET")
     * @ApiDoc(
     *     section="Song",
     *     resource=true,
     *     description="Gets songs by {offset} and {limit}",
     *     requirements={
     *          {"name"="offset", "dataType"="integer", "requirement"="\d+", "description"="Offset"},
     *          {"name"="limit", "dataType"="integer", "requirement"="\d+", "description"="Limit"}
     *     }
     * )
     * @param $offset
     * @param $limit
     * @return Response
     */
    public function allByOffsetAction($offset, $limit)
    {
        $songs = $this->getDoctrine()->getRepository('AppBundle:Song')->findBy(array(), null, $limit, $offset);
        return $this->prepareJsonResponse($songs);
    }

    /**
     * @Route("/{uuid}/stream",name="app_api_song_stream")
     * @ParamConverter("song", class="AppBundle:Song", options={"uuid"="uuid"})
     * @Method("GET")
     * @ApiDoc(
     *     resource=true,
     *     section="Song",
     *     description="Stream song by uuid",
     *     requirements={{"name"="uuid", "dataType"="string", "requirement"="\w+", "description"="Universal unique id of a song"}}
     * )
     * @param Song $song
     * @return BinaryFileResponse
     * @throws \Exception
     */
    public function streamAction(Song $song)
    {
        $file = $this->getParameter('music_path') . '/' . $song->getUuid();
        $response = new BinaryFileResponse($file, 200);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('connection', 'keep-alive');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $song->getArtist() . ' - ' . $song->getTitle() . '.mp3');
        return $response;
    }

    /**
     * @Route("/{uuid}", name="app_api_song_get")
     * @Method("GET")
     * @ParamConverter("song", class="AppBundle:Song", options={"uuid"="uuid"})
     * @param Song $song
     * @return JsonResponse
     * @ApiDoc(
     *     section="Song",
     *     resource=true,
     *     description="Get song by uuid",
     *     requirements={
     *          {"name"="uuid", "dataType"="string", "requirement"="\w+", "required"=true, "description"="Universal unique identity"}
     *     }
     * )
     */
    public function getAction(Song $song)
    {
        return $this->prepareJsonResponse($song);
    }

    /**
     * @Route("/top/{offset}", name="app_api_song_top", options={"expose"=true}, requirements={"offset"="\d+"})
     * @Method("GET")
     * @ApiDoc(
     *     resource=true,
     *     section="Song",
     *     description="Get songs by ranking, using offset",
     *     requirements={{"name"="offset", "dataType"="integer", "required"=true, "requirement"="\d+", "description"="Offset"}}
     * )
     * @param $offset
     * @return JsonResponse
     */
    public function topAction($offset)
    {
        $songs = $this->getDoctrine()->getRepository('AppBundle:Song')->createQueryBuilder('song')
            ->setMaxResults(50)
            ->setFirstResult($offset)
            ->orderBy('song.countPlay')
            ->getQuery()
            ->execute();
        return $this->prepareJsonResponse($songs);
    }
}