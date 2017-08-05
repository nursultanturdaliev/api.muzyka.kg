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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class SongFormatter implements FormatterInterface
{

	/** @var  TokenStorage */
	private $tokenStorage;

	private $entityManager;

	public function __construct(TokenStorage $tokenStorage, EntityManager $entityManager)
	{
		$this->tokenStorage  = $tokenStorage;
		$this->entityManager = $entityManager;
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
	 * @param Song $value
	 *
	 * @return array
	 */
	private function formatSong(Song $value)
	{
		return [
			'artist_as_one' => $value->getArtistAsOne(),
			'artists'       => self::formatArtists($value->getArtists()),
			'duration'      => $value->getDuration(),
			'id'            => $value->getId(),
			'uuid'          => $value->getUuid()->jsonSerialize(),
			'title'         => $value->getTitle(),
			'profileLocal'  => self::getProfileLocal($value->getArtists()),
			'history'       => count($value->getHistories()),
			'is_favourite'  => $this->isFavourite($value),
			'statistics'    => [
				'played'    => count($value->getHistories()),
				'favourite' => count($value->getFavourites())
			]
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
				'profileLocal' => $artist->getProfileLocal()
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
				return $artist->getProfileLocal();
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
}