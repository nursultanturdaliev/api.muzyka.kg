<?php

namespace AppBundle\Command;

use AppBundle\Entity\Artist;
use AppBundle\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ImportSongCommand extends ContainerAwareCommand
{

	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('app:song')
			->addArgument('name', InputArgument::REQUIRED)
			->addArgument('lastname', InputArgument::REQUIRED)
			->addArgument('song',InputArgument::REQUIRED)
			->addArgument('url', InputArgument::REQUIRED)
			->setDescription('AddMusic');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$name     = $input->getArgument('name');
		$lastname = $input->getArgument('lastname');
		$song     = $input->getArgument('song');
		$url      = $input->getArgument('url');

		$output->writeln($name);
		$output->writeln($lastname);
		$output->writeln($song);
		$output->writeln($url);

		$this->saveFile(array(implode(' ', array($name, $lastname))), $song, $url);
	}

	private function saveFile(array $artistNames, $songTitle, $url)
	{

		$fs = new Filesystem();
		if (!$this->songAlreadyExists($songTitle, $artistNames)) {

			$song = new Song();
			$song->setTitle($songTitle);
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
