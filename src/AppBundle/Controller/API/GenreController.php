<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/7/17
 * Time: 11:06 AM
 */

namespace AppBundle\Controller\API;


use AppBundle\Entity\Genre;
use Doctrine\ORM\AbstractQuery;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/genre")
 */
class GenreController extends ApiController
{
	/**
	 * @Route("/", name="app_api_genre_index")
	 * @Method("GET")
	 * @ApiDoc(
	 *     section="Genre",
	 *     resource=true,
	 *     description="Get all genres, without songs",
	 * )
	 */
	public function indexAction()
	{
		$genres = $this->getDoctrine()->getRepository('AppBundle:Genre')
					   ->createQueryBuilder('genre')
					   ->select('genre.id')
					   ->addSelect('genre.name')
					   ->getQuery()
					   ->execute(null, AbstractQuery::HYDRATE_SCALAR);
		return $this->prepareJsonResponse($genres);
	}

	/**
	 * @Route("/{id}/songs", name="app_api_genre_get", requirements={"id"="\d+"},options={"expose"=true})
	 * @Method("GET")
	 * @ParamConverter("genre", class="AppBundle:Genre")
	 * @ApiDoc(
	 *     section="Genre",
	 *     resource=true,
	 *     description="Get songs by genres",
	 *     requirements={{"name"="id","description"="Genre ID", "requirement"="\d+", "required"=true,
	 *     "dataType"="integer"}}
	 * )
	 * @param Genre $genre
	 *
	 * @return Response
	 */
	public function genreSongsAction(Genre $genre)
	{
		return $this->prepareJsonResponse($genre->getSongs());
	}
}