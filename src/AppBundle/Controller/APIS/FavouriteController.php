<?php

namespace AppBundle\Controller\APIS;

use AppBundle\Controller\API\ApiController;
use AppBundle\Entity\Favourite;
use AppBundle\Entity\Song;
use AppBundle\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/apis/favourite")
 */
class FavouriteController extends ApiController
{
	/**
	 * @Route("/")
	 * @Method("GET")
	 */
	public function indexAction()
	{
		/** @var User $user */
		$user       = $this->getUser();
		$favourites = $user->getFavourites();
		$formatted  = $this->get('app_formatter.favourite')->format($favourites);

		return $this->prepareJsonResponse($formatted);
	}

	/**
	 * @Route("/song/{uuid}")
	 * @ParamConverter("song", class="AppBundle:Song", options={"uuid"="uuid"})
	 * @Method("POST")
	 * @param Song $song
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function addSongAction(Song $song)
	{
		/** @var User $user */
		$user      = $this->getUser();
		$favourite = new Favourite();
		$favourite->setUser($user);
		$favourite->setSong($song);
		$em = $this->get('doctrine.orm.default_entity_manager');
		$em->persist($favourite);
		try {
			$em->flush($favourite);
		} catch (UniqueConstraintViolationException $ignore) {
			return new JsonResponse(array(), Response::HTTP_CONFLICT);
		}

		$formattedFavourite = $this->get('app_formatter.favourite')->format($favourite);

		return $this->prepareJsonResponse($formattedFavourite);
	}

    /**
     * @Route("/remove/song/{uuid}")
     * @ParamConverter("song", class="AppBundle:Song", options={"uuid"="uuid"})
     * @Method("POST")
     * @param Song $song
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeSongAction(Song $song)
    {
        /** @var User $user */
        $user      = $this->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $favourite = $em->createQueryBuilder('AppBundle:Favorite')
            ->findOneBy(array('user' => $user, 'song' => $song));

        if ( !$favourite ){
            return new JsonResponse(array(), Response::HTTP_NOT_FOUND);
        }

        $formattedFavourite = $this->get('app_formatter.favourite')->format($favourite);
        $em->remove($favourite);

        return $this->prepareJsonResponse($formattedFavourite);
    }
}
