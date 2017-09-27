<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 9/26/17
 * Time: 9:45 PM
 */

namespace AppBundle\Formatter;


use AppBundle\Entity\Playlist;

class PlaylistFormatter implements FormatterInterface
{
	private $songFormatter;

	public function __construct(SongFormatter $songFormatter)
	{
		$this->songFormatter = $songFormatter;
	}


	public function format($value)
	{
		if ($value instanceof Playlist) {
			$formatted = $this->formatPlaylist($value);
			return $formatted;
		}
		$formatted = [];
		/** @var Playlist $playlist */
		foreach ($value as $playlist) {
			$formatted[] = $this->formatPlaylist($playlist);
		}
		return $formatted;
	}

	private function formatPlaylist(Playlist $playlist)
	{
		return [
			'id'            => $playlist->getId(),
			'name'          => $playlist->getName(),
			'cover_photo'   => $playlist->getCoverPhoto(),
			'profile_photo' => $playlist->getProfilePhoto(),
			'songs'         => $this->songFormatter->format($playlist->getSongs())
		];
	}
}