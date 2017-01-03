<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/song")
 */
class SongController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $songQuery = $this->getDoctrine()->getRepository('AppBundle:Song')->findAllQuery();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($songQuery, $request->query->getInt('page', 1), 20);
        return array('pagination' => $pagination);
    }

    /**
     * @Route("/json")
     */
    public function jsonAction()
    {
        $songsAsArray = $this->getDoctrine()->getRepository('AppBundle:Song')->findAllQuery()->getQuery()->getArrayResult();
        return new JsonResponse($songsAsArray);
    }
}
