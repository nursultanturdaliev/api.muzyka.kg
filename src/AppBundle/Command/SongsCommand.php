<?php

namespace AppBundle\Command;

use AppBundle\Entity\Song;
use Doctrine\ORM\EntityManager;
use LyricsBundle\Entity\Song as LyricsSong;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SongsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:songs')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $counter = 0;
        /** @var EntityManager $manager */
        $manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        /** @var LyricsSong $song */
        foreach ($manager->getRepository('LyricsBundle:Song')->findAll() as $song) {

            /** @var Song[] $appSong */
            $appSongs = $manager->getRepository('AppBundle:Song')->findBySongAndArtist($song->getName(),$song->getArtist()->getName());
            if (sizeof($appSongs) === 0) {
                continue;
            }
            /** @var Song $appSong */
            foreach ($appSongs as $appSong) {
                $counter++;
                $appSong->setLyrics($song->getContent());
                $manager->persist($appSong);
                if ($counter % 100 === 0) {
                    $manager->flush();
                    $output->writeln('100');
                }
            }

        }

        $manager->flush();
    }
}
