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
		if ($value instanceof FavouriteFormatter) {
			$formatted = self::formatFavourite($value);
			return $formatted;
		}
		if ($value instanceof PersistentCollection and !($value->first() instanceof Favourite)) {
			throw new \InvalidArgumentException();
		}

		$formatted = [];
		foreach ($value as $favourite) {
			$formatted[] = self::formatFavourite($favourite);
		}

		return $formatted;
	}

	private static function formatFavourite(Favourite $favourite)
	{
		return [
			'id'   => $favourite->getId(),
			'song' => SongFormatter::format($favourite->getSong())
		];
	}
}