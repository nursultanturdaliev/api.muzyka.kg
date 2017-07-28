<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 7/25/17
 * Time: 10:24 PM
 */

namespace AppBundle\Formatter;


use AppBundle\Entity\Artist;
use AppBundle\Entity\Song;

class ArtistFormatter implements FormatterInterface
{

	/**
	 * @param Artist|Artist[] $value
	 *
	 * @return array
	 */
	public static function format($value)
	{
		$formattedArray = [];
		if ($value instanceof Artist) {
			$preFormatted          = self::formatArtist($value);
			$preFormatted['songs'] = self::formatSongs($value);
			return $preFormatted;
		}

		foreach ($value as $artist) {
			$formattedArray[] = self::formatArtist($artist);
		}


		usort($formattedArray,function($a, $b){
			return $b['numberOfSongs'] - $a['numberOfSongs'];
		});
		return $formattedArray;
	}

	private static function formatArtist(Artist $value)
	{
		return [
			'id'            => $value->getId(),
			'name'          => $value->getName(),
			'lastname'      => $value->getLastname(),
			'instagram'     => $value->getInstagram(),
			'profile'       => $value->getProfile(),
			'profileLocal'  => $value->getProfileLocal(),
			'hasProfileLocal'=> boolval($value->getProfileLocal()),
			'numberOfSongs' => $value->getSongs()->count()
		];
	}

	/**
	 * @param Artist $artist
	 *
	 * @return array
	 */
	private static function formatSongs($artist)
	{
		$formatted = [];
		/** @var Song $song */
		foreach ($artist->getSongs() as $song) {
			$formatted[] = self::formatSong($song);
		}
		return $formatted;
	}

	/**
	 * @param Song $song
	 *
	 * @return array
	 */
	private static function formatSong($song)
	{
		return [
			'uuid'           => $song->getUuid()->jsonSerialize(),
			'title'          => $song->getTitle(),
			'duration'       => $song->getDuration(),
			'artist_as_one' => $song->getArtistAsOne()
		];
	}
}