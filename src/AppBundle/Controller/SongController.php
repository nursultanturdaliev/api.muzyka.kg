<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class SongController extends Controller
{
    /**
     * @Route("/list")
     */
    public function listAction(Request $request)
    {
        $entities = $this->container->get('app.song')->getAllSongs();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($entities, $request->query->getInt('page', 1), 20);
        return $this->render('AppBundle:Song:list.html.twig', array(
            'entities' => $pagination
        ));
    }

    /**
     * @Route("/get/json")
     */
    public function jsonAction()
    {
        $entities = $this->container->get('app.song')->getAllSongs();
        $json = json_encode($entities, JSON_UNESCAPED_UNICODE);
        header("Content-type:application/json");
        header('Content-Disposition: attachment; filename= song.json');
        echo $json;
        return new Response();
    }

    /**
     * @Route("/api/download/{id}/{token}")
     */
    public function downloadAction($id, $token)
    {
        if ($token != $this->container->getParameter('access_token')) {
            throw new Exception("Invalid token credentials", 400);
        }
        $entity = $this->container->get('app.song')->getSong($id);
        $fileurl = $entity->getPath();
        header("Content-type:audio/mpeg");
        header('Content-Disposition: attachment; filename=' . $entity->getArtist() . ' - ' . $entity->getTitle() . '.mp3');
        header('Content-Transfer-Encoding: binary');
        readfile($fileurl);
        return new Response();
    }

}
