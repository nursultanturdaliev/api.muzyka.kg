<?php

namespace LyricsBundle\Controller\API;

use AppBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/api/lyrics/artist")
 * Class ArtistController
 * @package LyricsBundle\Controller
 */
class ArtistController extends ApiController
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $artists = $this->get('doctrine.orm.entity_manager')->getRepository('LyricsBundle:Artist')->findAll();
        return $this->prepareJsonResponse($artists);
    }
}
