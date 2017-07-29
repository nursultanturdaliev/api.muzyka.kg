<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 7/29/17
 * Time: 7:58 PM
 */

namespace AppBundle\Formatter;


use AppBundle\Entity\Favourite;
use Doctrine\ORM\PersistentCollection;

class FavouriteFormatter implements FormatterInterface
{

	/**
	 * @param Favourite|PersistentCollection $value
	 *
	 * @return array
	 */
	public static function format($value)
	{
		if ($value instanceof Favourite) {
			$formatted         = [];
			$formatted['id']   = $value->getId();
			$formatted['song'] = SongFormatter::format($value->getSong());
			return $formatted;
		}
		if ($value instanceof PersistentCollection and !($value->first() instanceof Favourite)) {
			throw new \InvalidArgumentException();
		}

		$formatted               = [];
		$formatted['songs']      = [];
		$formatted['favourites'] = [];
		foreach ($value as $favourite) {
			$formatted['favourites'][] = self::formatFavourite($favourite);
			$formatted['songs'][]      = SongFormatter::format($favourite->getSong());
		}


		return $formatted;
	}

	private static function formatFavourite(Favourite $favourite)
	{
		return [
			'id'     => $favourite->getId(),
			'songId' => $favourite->getSong()->getId()
		];
	}
}