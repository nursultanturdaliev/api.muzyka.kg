<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 7/30/17
 * Time: 1:39 AM
 */

namespace AppBundle\Formatter;


use AppBundle\Entity\History;
use Doctrine\Common\Collections\Collection;

class HistoryFormatter implements FormatterInterface
{

	/**
	 * @param History|History[]|Collection $value
	 *
	 * @return array
	 */
	public static function format($value)
	{
		if ($value instanceof History) {
			$formatted = self::formatHistory($value);
			return $formatted;
		}
		$formatted = [];
		foreach ($value as $history) {
			$formatted[] = self::formatHistory($history);
		}
		return $formatted;
	}

	/**
	 * @param $value
	 *
	 * @return array
	 */
	private static function formatHistory(History $value)
	{
		return [
			'id'        => $value->getId(),
			'song'      => SongFormatter::format($value->getSong()),
			'startedAt' => $value->getStartedAt()->format('Y.m.d H:i:s'),
			'stoppedAt' => $value->getStoppedAt() ? $value->getStoppedAt()->format('Y-m-d H:i:s') : null
		];
	}
}