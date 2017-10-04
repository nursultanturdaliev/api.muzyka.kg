<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/3/17
 * Time: 5:08 PM
 */

namespace AppBundle\Controller\API;

use AppBundle\Entity\Song;
use AppBundle\Repository\SongRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/song")
 */
class SongController extends ApiController
{
	const MAXIMUM_SONG_RESPONSE = 100;

	/**
	 * @Route("/search/{text}", name="app_api_song_search", options={"expose"=true})
	 *
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Search songs"
	 * )
	 *
	 * @param $text
	 *
	 * @return Response
	 */
	public function searchAction($text)
	{
		/** @var SongRepository $searchRepository */
		$searchRepository = $this->getDoctrine()->getRepository('AppBundle:Song');
		$songs            = $searchRepository->search($text);
		$formattedSongs   = $this->get('app_formatter.song')->format($songs);
		return $this->prepareJsonResponse($formattedSongs);
	}

	/**
	 * @Route("/random/{max}", name="app_api_song_random", options={"expose"=true}, requirements={"max"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Random Songs",
	 *     requirements={{"name"="max", "dataType"="integer", "requirement"="\d+", "description"="Maximum result"}}
	 * )
	 * @param Request $request
	 * @param $max int
	 *
	 * @return Response
	 */
	public function randomAction(Request $request, $max)
	{
		$artistId = $request->query->get('artistId');
		$songs    = $this->get('doctrine.orm.default_entity_manager')->getRepository('AppBundle:Song')
						 ->getRandomSongs($max, $artistId);

		return $this->prepareJsonResponse($this->get('app_formatter.song')->format($songs));
	}

	/**
	 * @Route("/topdownloads/{max}", name="app_api_song_top_downloads", options={"expose"=true},
	 *                               requirements={"max"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Returns Top Downloads",
	 *     requirements={{"name"="max", "dataType"="integer", "requirement"="\d+", "description"="Maximum number of
     *     songs"}}
     * )
	 * @param $max
	 *
	 * @return Response
	 */
	public function topDownloadsAction($max)
	{
		$songs = $this->get('doctrine.orm.default_entity_manager')->getRepository('AppBundle:Song')
					  ->topDownloads($max);
		return $this->prepareJsonResponse($songs);
	}

	/**
	 * @Route("/status/new",name="app_api_song_new_release", options={"expose"=true},
	 *                                                              requirements={"limit"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Returns new releases",
	 *     requirements={{"name"="max", "dataType"="integer", "requirement"="\d+", "description"="Maximum number of
     *     songs"}}
     * )
	 * @param $limit
	 *
	 * @return Response
	 */
	public function statusNewAction()
	{

		$songs = $this->get('doctrine.orm.default_entity_manager')
					  ->getRepository('AppBundle:Song')
					  ->newReleases();

		$formattedSongs = $this->get('app_formatter.song')->format($songs);
		return $this->prepareJsonResponse($formattedSongs);
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
	 * @Route("/page/{page}/", name="app_api_song_pagination", requirements={"page"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     section="Song",
	 *     resource=true,
	 *     description="Gets songs by {offset} and {limit}",
	 *     requirements={
	 *          {"name"="page", "dataType"="integer", "requirement"="\d+", "description"="Page"},
	 *     }
	 * )
	 * @param $page
	 *
	 * @return Response
	 */
	public function pageAction($page = 1)
	{
		$songs          = $this->getDoctrine()->getRepository('AppBundle:Song')
							   ->findBy(array(), array('title' => 'ASC'), self::MAXIMUM_SONG_RESPONSE, self::MAXIMUM_SONG_RESPONSE * abs($page - 1));
		$formattedSongs = $this->get('app_formatter.song')->format($songs);
		return $this->prepareJsonResponse($formattedSongs);
	}

	/**
	 * @Route("/{slug}", name="app_api_song_get")
	 * @Method("GET")
	 * @ApiDoc(
	 *     section="Song",
	 *     resource=true,
	 *     description="Get song by slug",
	 *     requirements={
	 *          {"name"="slug", "dataType"="string", "requirement"="\w+", "required"=true, "description"="Universal
     *          unique identity"}
     *     }
	 * )
	 *
	 * @param $slug
	 *
	 * @return JsonResponse
	 *
	 */
	public function getAction($slug)
	{
		$song = $this->getDoctrine()->getRepository('AppBundle:Song')->findOneBySlug($slug);
		$formattedSongs   = $this->get('app_formatter.song')->format($song);
		return $this->prepareJsonResponse($formattedSongs);
	}

	/**
	 * @Route("/top/{page}", name="app_api_song_top", options={"expose"=true}, requirements={"page"="\d+"})
	 * @Method("GET")
	 * @ApiDoc(
	 *     resource=true,
	 *     section="Song",
	 *     description="Get songs by ranking, using offset",
	 *     requirements={{"name"="page", "dataType"="integer", "required"=true, "requirement"="\d+", "description"="Limit"}}
	 * )
	 *
	 * @param $page
	 *
	 * @return JsonResponse
	 *
	 */
	public function topAction($page)
	{
		$songs = $this->getDoctrine()
					  ->getRepository('AppBundle:Song')
					  ->top()
					  ->setMaxResults(20)
					  ->setFirstResult(abs($page - 1) * 10)
					  ->getResult();

		$formattedSongs = $this->get('app_formatter.song')->formatTop($songs);
		return $this->prepareJsonResponse($formattedSongs);
	}



    private function slug($text)
    {

        //$text = strtolower($text);
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        // trim
        $text = trim($text, '-');

        $text = mb_strtolower($text);


        $code = [
            '246', '351', '629', '1072', '1073', '1074', '1075', '1076',
            '1077', '1078', '1079', '1080', '1081', '1082', '1083', '1084', '1085',
            '1086', '1087', '1088', '1089', '1090', '1091', '1092', '1093', '1094',
            '1095', '1096', '1097', '1098', '1099', '1100', '1101', '1102', '1103',
            '1105', '1110', '1187', '1199', '1226', '1257',
        ];
        $cyr = [
            'ö', 'ş', 'ɵ', 'а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и', 'й',
            'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш',
            'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'ё', 'і', 'ң', 'ү', 'ӊ', 'ө',
        ];


        $lat = [
            'o', 'sh', 'o', 'a', 'b', 'v', 'g', 'd', 'e', 'zh', 'z', 'i', 'i',
            'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh',
            'sh', '-', 'y', '-', 'e', 'yu', 'ya', 'yo', 'i', 'n', 'u', 'n', 'o',
        ];


        $text = str_replace($cyr, $lat, $text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);
        // lowercase


        return $text;
    }
}