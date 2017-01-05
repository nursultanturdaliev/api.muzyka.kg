<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/5/17
 * Time: 5:46 PM
 */

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/artist",)
 */
class ArtistController extends Controller
{
    /**
     * @Route("/", name="app_artist_index")
     * @Template()
     */
    public function indexAction()
    {
        $artists = $this->getDoctrine()->getRepository('AppBundle:Artist')->findAll();
        return array('artists' => $artists);
    }
}