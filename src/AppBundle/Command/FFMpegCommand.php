<?php

namespace AppBundle\Command;

use AppBundle\Entity\Song;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Media\Audio;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FFMpegCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:ffmpeg:audio')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $fileSystem = new Filesystem();
        $finder = new Finder();
        $ffmpeg = FFMpeg::create();
        foreach ($finder->files()->in($this->getMusicDir()) as $fileInfo) {
            $song = $this->getSong($fileInfo->getBasename());
            if($song instanceof Song){
                /** @var Audio $audio */
                $audio = $ffmpeg->open($fileInfo->getPathname());
                $audio->filters()->addMetadata(
                    array(
                        'title'=>$song->getTitle(),
                        'artist'=>$song->getArtist()->getName(),
                        'artwork'=>'logo.jpg',
                        'album'=>'MUZYKA.KG'
                    )
                );

                $audio->save(new Mp3(),$fileInfo->getPathname() . '.mp3');
                $fileSystem->rename($fileInfo->getPathname() . '.mp3',$fileInfo->getPathname(),true);
                $output->writeln($audio->getPathfile());
            }
        }


    }

    private function getMusicDir()
    {
        $music_dir = $this->getContainer()->getParameter('music_path');
        $kernel_dir = $this->getContainer()->get('kernel')->getRootDir();
        return $kernel_dir . '/../' . $music_dir;
    }

    private function getSong($uuid)
    {
        try{
            $song = $this->getContainer()->get('doctrine.orm.default_entity_manager')
                ->getRepository('AppBundle:Song')
                ->findOneByUuid($uuid);
            return $song;
        }catch (\Exception $ignore){
            return null;
        }
    }
}
