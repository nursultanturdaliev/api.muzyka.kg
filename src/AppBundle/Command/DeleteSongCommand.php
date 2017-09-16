<?php

namespace AppBundle\Command;

use AppBundle\Entity\Song;
use Doctrine\ORM\EntityManager;
use LyricsBundle\Entity\Song as LyricsSong;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class DeleteSongCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:deletesong')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $manager */
        $manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $connection = $manager->getConnection();
        $statement = $connection->prepare("SELECT id, uuid FROM app_songs ORDER By id");
        $statement->execute();
        $results = $statement->fetchAll();
        $fs = new Filesystem();
        $baseMusicDir = $this->getContainer()->getParameter('music_path');
        $counter = 0;
        foreach ($results as $item) {
            $id = $item["id"];
            $song = $manager->getRepository('AppBundle:Song')->find($id);
            if (!$song) {
                $histories = $manager->getRepository('AppBundle:History')->findBySong($id);
                $favorites = $manager->getRepository('AppBundle:Favourite')->findBySong($id);
                foreach ($histories as $history){
                    $manager->remove($history);
                    $manager->flush();
                }
                foreach ($favorites as $favorite){
                    $manager->remove($favorite);
                    $manager->flush();
                }
                $file = $baseMusicDir . '/' . $item["uuid"];
                $fs->remove($file);
                $stmt = $connection->prepare("DELETE FROM app_songs WHERE id =:id");
                $stmt->bindParam('id', $id);
                $stmt->execute();
                $counter++;
            }
        }
        $output->writeln($counter." files deleted");
    }
}