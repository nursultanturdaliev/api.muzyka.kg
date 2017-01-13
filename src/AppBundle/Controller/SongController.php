<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/13/17
 * Time: 2:48 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Song;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @Route("/song")
 */
class SongController extends Controller
{

    /**
     * @Route("/{uuid}/stream",name="app_song_stream")
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
}