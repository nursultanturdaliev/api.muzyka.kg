<?php

namespace AppBundle\Command;


use AppBundle\Entity\Artist;
use AppBundle\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;

class SuperInfoCommand extends ContainerAwareCommand
{
	const URL = 'http://www.super.kg/media/audio/';

	const AUDIO_STREAM_BASE_URL = 'http://media.super.kg/media/audio/';

	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('app:superinfo')
			->setDefinition(array(
				new InputArgument('from', InputArgument::REQUIRED, 'Page From'),
				new InputArgument('to', InputArgument::REQUIRED, 'Page To'),
			))
			->setDescription('Downloads music from super info');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$from = $input->getArgument('from');
		$to   = $input->getArgument('to');

		libxml_use_internal_errors(true);

		while ($from <= $to) {
			$html = file_get_contents(self::URL . '?pg=' . $from);

			/** @var Crawler $crawler */
			$crawler = new Crawler($html);


			$crawler->filter('.audio-block .audio-item')
					->each(function ($element) use ($output) {
						/** @var Crawler $element */
						$fileName = $element->attr('data-file');
						$title    = $element->filter('.audio-item-title a')->attr('title');
						$duration = $element->filter('.info-item.length')->text();
						$duration = substr($duration, 3, 5);

						$output->writeln($this->saveFile($fileName, $title, $duration));
					});
			$from += 1;
		}
	}

	private function saveFile($fileName, $musicAndTitle, $duration)
	{

		$fs         = new Filesystem();
		$artistName = substr($musicAndTitle, 8, strpos($musicAndTitle, '</') - 8);
		$songTitle  = substr($musicAndTitle, strpos($musicAndTitle, '"') + 1, strrpos($musicAndTitle, '"') - strpos($musicAndTitle, '"') - 1);

		$artistNames = explode(',', $artistName);
		if (!$this->songAlreadyExists($songTitle, $artistNames)) {
			$oldUrl = self::AUDIO_STREAM_BASE_URL . $fileName;

			$song = new Song();
			$song->setTitle($songTitle);
			$song->setDuration($duration);
			$this->save($song);

			foreach ($artistNames as $artistName) {
				$artist = $this->getArtistOrCreate($artistName);
				$artist->addSong($song);
				$this->save($artist);
			}

			$fs->copy($oldUrl, $this->getBaseMusicDir() . '/' . $song->getUuid());
			if (sizeof($song->getArtists()) === 1) {
				return $song->getTitle() . ' ' . $song->getArtists()[0]->getName();
			} else {
				return $song->getTitle();
			}
		} else {
			return 'Already Exists: ' . $songTitle;
		}
	}

	private function songAlreadyExists($title, $artistNames)
	{
		$data = $this->getContainer()->get('doctrine.orm.entity_manager')
					 ->getRepository('AppBundle:Song')
					 ->findBySongAndArtist($title, $artistNames);

		return sizeof($data) > 0;
	}

	private function getArtistOrCreate($artistName)
	{
		$artistName = trim($artistName);

		$manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

		$artist = $manager
			->getRepository('AppBundle:Artist')
			->findOneByName($artistName);

		if ($artist instanceof Artist) {
			return $artist;
		} else {
			$artist = new Artist();
			$artist->setName($artistName);
			return $this->save($artist);
		}
	}

	private function save(&$entity)
	{

		$manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
		$manager->persist($entity);
		$manager->flush();
		return $entity;
	}

	private function getBaseMusicDir()
	{
		return $this->getContainer()->getParameter('music_path');
	}

	private function removeSong($oldUrl)
	{
		$manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
		$song    = $manager->getRepository('AppBundle:Song')
						   ->findOneByOldUrl($oldUrl);

		if ($song instanceof Song) {
			$manager->remove($song);
			$manager->flush();
		}
	}
}
