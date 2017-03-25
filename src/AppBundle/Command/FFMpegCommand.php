<?php

namespace AppBundle\Command;

use AppBundle\Entity\Song;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Media\Audio;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FFMpegCommand extends ContainerAwareCommand
{
	const FROM   = "from";
	const AMOUNT = "amount";

	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('app:ffmpeg:audio')
			->addArgument(self::FROM, InputArgument::REQUIRED)
			->addArgument(self::AMOUNT, InputArgument::REQUIRED)
			->setDescription('Edit audio files.');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{

		$fileSystem = new Filesystem();
		$finder     = new Finder();
		$ffmpeg     = FFMpeg::create();

		$from    = (int)$input->getArgument(self::FROM);
		$amount  = (int)$input->getArgument(self::AMOUNT);
		$counter = 0;
		foreach ($finder->files()->in($this->getMusicDir()) as $fileInfo) {

			$counter++;
			if ($counter < $from) {
				continue;
			}
			if ($counter > $from + $amount) {
				break;
			}

			try {
				$uuid = Uuid::fromString($fileInfo->getBasename());
				$song = $this->getSong($uuid);
				if ($song instanceof Song) {
					$output->writeln($song->getTitle());
					/** @var Audio $audio */
					$audio = $ffmpeg->open($fileInfo->getPathname());
					$audio->filters()->addMetadata(
						array(
								'title'   => $song->getTitle(),
								'artist'  => $song->getArtists()->getName(),
								'artwork' => 'logo.jpg',
								'album'   => 'MUZYKA.KG'
						)
					);

					$audio->save(new Mp3(), $fileInfo->getPathname() . '.mp3');
					$fileSystem->rename($fileInfo->getPathname() . '.mp3', $fileInfo->getPathname(), true);
					$output->writeln($audio->getPathfile());

				} else {
					$output->writeln('Skipped');
				}
			} catch (\InvalidArgumentException $e) {
				$output->writeln($e->getMessage());
			}
		}


	}

	private function getMusicDir()
	{
		$music_dir  = $this->getContainer()->getParameter('music_path');
		$kernel_dir = $this->getContainer()->get('kernel')->getRootDir();
		return $kernel_dir . '/../' . $music_dir;
	}

	private function getSong($uuid)
	{
		$song = $this->getContainer()->get('doctrine.orm.default_entity_manager')
					 ->getRepository('AppBundle:Song')
					 ->findOneByUuid($uuid);
		return $song;
	}
}
