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
    private $placeholders = ['placeholder_one', 'placeholder_two'];

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
			$preFormatted = self::formatArtist($value);
			$songs        = $value->getSongs()->toArray();
			usort($songs, function (Song $a, Song $b) {
				return strcmp($a->getTitle(), $b->getTitle());
			});
			$preFormatted['songs'] = $this->songFormatter->format($songs);
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
			'profileLocal'    => $this->profileLocal($value->getProfileLocal()),
			'hasProfileLocal' => $value->hasProfileLocal(),
			'numberOfSongs'   => $value->getSongs()->count()
		];
	}

    private function profileLocal($profileLocal)
    {
        if (!$profileLocal) {
            $profileLocal = $this->placeholders[rand(0, 1)];
        }
        return 'http://api-muzyka.aio.kg/uploads/artist/profile/' . $profileLocal . '.jpg';
    }
}