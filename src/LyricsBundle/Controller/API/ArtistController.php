<?php

namespace LyricsBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/lyrics/artist")
 * Class ArtistController
 * @package LyricsBundle\Controller
 */
class ArtistController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $jms = $this->get('jms_serializer');
        $artists = $this->get('doctrine.orm.entity_manager')->getRepository('LyricsBundle:Artist')->findAll();
        return new JsonResponse($jms->serialize($artists, 'json'));
    }
}
