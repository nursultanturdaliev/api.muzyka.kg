<?php

namespace AppBundle\Command;

use AppBundle\Entity\Artist;
use AppBundle\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;

class AppObondoruCommand extends ContainerAwareCommand
{
	const URL = 'http://obondoru.kg/category/zhany-yrlar/';

	protected function configure()
	{
		$this
			->setName('app:obondoru')
			->setDescription('Download new songs from obondoru.kg');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$html = file_get_contents(self::URL);

		/** @var Crawler $crawler */
		$crawler = new Crawler($html);

		$crawler->filter('#blog-entries >article h2.blog-entry-title.entry-title>a')
				->each(function ($element) use ($output) {
					$rawArtistAndSong = $element->attr('title');

					$link = $element->attr('href');

					$songHTML = file_get_contents($link);

					$songCrawler = new Crawler($songHTML);

					$node = $songCrawler->filter('div.entry.clr >p >a')->first();
					$url  = $node->attr('href');

					list($artistName, $songTitle) = preg_split('/«/', $rawArtistAndSong);

					$artistName = trim($artistName);
					$songTitle  = trim($songTitle);
					$songTitle  = str_replace('»', '', $songTitle);

					$output->writeln($rawArtistAndSong);
					$artistNames = array($artistName);
					$this->saveFile($artistNames, $songTitle, $url);

				});

	}

	private function saveFile(array $artistNames, $songTitle, $url)
	{

		$fs = new Filesystem();
		if (!$this->songAlreadyExists($songTitle, $artistNames)) {

			$song = new Song();
			$song->setTitle($songTitle);
			$song->setIsNew(true);
			$this->save($song);
			foreach ($artistNames as $artistName) {
				$artist = $this->getArtistOrCreate($artistName);
				$artist->addSong($song);
				$this->save($artist);
			}

			$fs->copy($url, $this->getBaseMusicDir() . '/' . $song->getUuid());
			if (sizeof($song->getArtists()) === 1) {
				return $song->getTitle() . ' ' . $song->getArtists()[0]->getName();
			} else {
				return $song->getTitle();
			}
		} else {
			return 'Already Exists: ' . $songTitle;
		}
	}

	private function songAlreadyExists($title, array $artistNames)
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

}
