<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 7/26/17
 * Time: 8:58 AM
 */

namespace AppBundle\Formatter;


use AppBundle\Entity\Artist;
use AppBundle\Entity\Song;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class SongFormatter implements FormatterInterface
{


	private $tokenStorage;
	private $entityManager;
	private $container;

    private $placeholders = ['placeholder_one', 'placeholder_two'];

	public function __construct(TokenStorage $tokenStorage, EntityManager $entityManager, ContainerInterface $container)
	{
		$this->tokenStorage  = $tokenStorage;
		$this->entityManager = $entityManager;
		$this->container = $container;
	}

	/**
	 * @param PersistentCollection|Song $value
	 *
	 * @return array
	 */
	public function format($value)
	{

		$formattedArray = [];
		if ($value instanceof Song) {
			$preFormatted            = self::formatSong($value);
			$preFormatted['artists'] = self::formatArtists($value->getArtists());
			return $preFormatted;
		}

		foreach ($value as $song) {
			$formattedArray[] = self::formatSong($song);
		}

		return $formattedArray;
	}

	/**
	 * @param Song $song
	 *
	 * @return array
	 */
	private function formatSong(Song $song)
	{
		return [
				'artist_as_one' => $song->getArtistAsOne(),
				'artists'       => $this->formatArtists($song->getArtists()),
				'composed_by'   => $song->getComposedBy(),
				'cover_photo'   => $this->getCoverPhoto($this->container->getParameter('base_url'), $song),
				'duration'      => $song->getDuration(),
				'id'            => $song->getId(),
				'history'       => count($song->getHistories()),
				'is_favourite'  => $this->isFavourite($song),
				'is_new'        => $song->getIsNew(),
				'lyrics'        => $song->getLyrics(),
				'profileLocal'  => $this->getProfileLocal($song->getArtists()),
				'released_at'   => $song->getReleasedAt(),
				'statistics'    => [
						'played'    => count($song->getHistories()),
						'favourite' => count($song->getFavourites())
				],
				'title'         => $song->getTitle(),
				'uuid'          => $song->getUuid()->jsonSerialize(),
				'written_by'    => $song->getWrittenBy(),
				'youtube'       => $song->getYoutube(),
		];
	}

	/**
	 * @param Artist[]| PersistentCollection $artists
	 *
	 * @return array
	 */
	private function formatArtists($artists)
	{
		$formattedArray = [];
		/** @var Artist $artist */
		foreach ($artists as $artist) {
			$formattedArray[] = [
				'id'           => $artist->getId(),
				'lastname'     => $artist->getLastname(),
				'name'         => $artist->getName(),
				'profileLocal' => $this->profileLocal($artist->getProfileLocal())
			];
		}

		return $formattedArray;

	}

	/**
	 * @param PersistentCollection $artists
	 *
	 * @return bool
	 */
	private function getProfileLocal($artists)
	{
		if (!$artists) {
			return false;
		}
		/** @var Artist $artist */
		foreach ($artists as $artist) {
			if ($artist->getProfileLocal()) {
				return $this->profileLocal($artist->getProfileLocal());
			}
		}
	}

	public function formatTop($songs)
	{
		$formatted = [];
		foreach ($songs as $song) {
			$formattedSong = self::formatSong($song[0]);
			$formatted[]   = $formattedSong;
		}
		return $formatted;
	}

	private function isFavourite(Song $song)
	{
		if (!$this->tokenStorage->getToken()) {
			return false;
		}

		/** @var User $user */
		$user = $this->tokenStorage->getToken()->getUser();
		if (!$user) {
			return false;
		}

		return $this->entityManager->getRepository('AppBundle:Favourite')->isFavourite($user, $song);
	}

	private function getCoverPhoto($baseUrl, Song $song)
	{
		return $baseUrl . 'uploads/songs/cover/' . $song->getUuid()->jsonSerialize();
	}

    private function profileLocal($profileLocal)
    {
        if (!$profileLocal) {
            $profileLocal = $this->placeholders[rand(0, 1)];
        }
        return 'http://api-muzyka.aio.kg/uploads/artist/profile/' . $profileLocal . '.jpg';
    }
}