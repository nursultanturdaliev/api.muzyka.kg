<?php

namespace AppBundle\Command;

use AppBundle\Entity\Artist;
use AppBundle\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArtistPurifierCommand extends ContainerAwareCommand
{
	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('app:artist_purifier')
			->setDescription('Hello PhpStorm');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$entityManager   = $this->getContainer()->get('doctrine.orm.default_entity_manager');
		$artistsToRemove = [];
		$artists         = $this->getContainer()->get('doctrine.orm.default_entity_manager')->getRepository('AppBundle:Artist')
								->createQueryBuilder('artist')
								->where("artist.name LIKE ' %'")
								->getQuery()
								->execute();
		/** @var Artist $artist */
		foreach ($artists as $artist) {
			/** @var Artist $realArtist */
			$realArtist = $this->getContainer()->get('doctrine.orm.default_entity_manager')->getRepository('AppBundle:Artist')
							   ->createQueryBuilder('artist')
							   ->where('artist.name = :name')
							   ->setParameter('name', ltrim($artist->getName()))
							   ->getFirstResult();
			if ($realArtist) {
				/** @var Song $song */
				foreach ($artist->getSongs() as $song) {
					$song->addArtist($realArtist);
					$song->removeArtist($artist);
					$entityManager->persist($song);
					$entityManager->flush();
				}
				$artistsToRemove[] = $artist;
			}else{
				$output->writeln($artist->getId());
			}
		}

		foreach ($artistsToRemove as $artist) {
			$entityManager->remove($artist);
			$entityManager->flush();
		}
	}
}
