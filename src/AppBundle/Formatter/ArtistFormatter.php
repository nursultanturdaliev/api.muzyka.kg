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

	/** @var  SongFormatter */
	private $songFormatter;

	public function __construct(SongFormatter $songFormatter)
	{
		$this->songFormatter = $songFormatter;
	}

	/**
	 * @param Artist|Artist[] $value
	 *
	 * @return array
	 */
	public function format($value)
	{
		$formattedArray = [];
		if ($value instanceof Artist) {
			$preFormatted          = self::formatArtist($value);
			$preFormatted['songs'] = $this->songFormatter->format($value->getSongs());
			return $preFormatted;
		}

		foreach ($value as $artist) {
			$formattedArray[] = self::formatArtist($artist);
		}


		usort($formattedArray, function ($a, $b) {
			return $b['numberOfSongs'] - $a['numberOfSongs'];
		});
		return $formattedArray;
	}

	private function formatArtist(Artist $value)
	{
		return [
			'id'              => $value->getId(),
			'name'            => $value->getName(),
			'lastname'        => $value->getLastname(),
			'instagram'       => $value->getInstagram(),
			'profile'         => $value->getProfile(),
			'profileLocal'    => $value->getProfileLocal(),
			'hasProfileLocal' => $value->hasProfileLocal(),
			'numberOfSongs'   => $value->getSongs()->count()
		];
	}
}