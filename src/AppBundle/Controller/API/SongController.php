<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/3/17
 * Time: 5:08 PM
 */

namespace AppBundle\Controller\API;

use AppBundle\Entity\Song;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/song")
 */
class SongController extends Controller
{
    /**
     * @Route("/{id}/stream",name="app_api_song_stream")
     * @ParamConverter("song", class="AppBundle:Song", options={"id"="id"})
     * @Method("GET")
     * @param Song $song
     * @return BinaryFileResponse
     * @throws \Exception
     */
    public function streamAction(Song $song)
    {
        $file = $this->getParameter('music_path') . '/' . $song->getUuid();
        $response = new BinaryFileResponse($file, 200);
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set('connection', 'keep-alive');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $song->getArtist() . ' - ' . $song->getTitle() . '.mp3');
        return $response;
    }

    /**
     * @Route("/",name="app_api_song_index")
     */
    public function indexAction()
    {
        $songsAsArray = $this->getDoctrine()->getRepository('AppBundle:Song')->findAllQuery()->getQuery()->getArrayResult();
        return new JsonResponse($songsAsArray);
    }
}