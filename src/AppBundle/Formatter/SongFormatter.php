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
use Doctrine\ORM\PersistentCollection;

class SongFormatter implements FormatterInterface
{

	/**
	 * @param PersistentCollection|Song $value
	 *
	 * @return array
	 */
	public static function format($value)
	{

		$formattedArray = [];
		if ($value instanceof Song) {
			$preFormatted            = self::formatSong($value);
			$preFormatted['artists'] = self::formatArtists($value);
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
	private static function formatSong(Song $value)
	{
		return [
			'artist_as_one' => $value->getArtistAsOne(),
			'artists'       => self::formatArtists($value->getArtists()),
			'duration'      => $value->getDuration(),
			'id'            => $value->getId(),
			'uuid'          => $value->getUuid()->jsonSerialize(),
			'title'         => $value->getTitle(),
			'profileLocal'  => self::getProfileLocal($value->getArtists())
		];
	}

	/**
	 * @param Artist[]| PersistentCollection $artists
	 *
	 * @return array
	 */
	private static function formatArtists($artists)
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
	private static function getProfileLocal($artists)
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
}