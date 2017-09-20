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

class ArtistGenderCommand extends ContainerAwareCommand
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:artistgender')
            ->setDescription('Set gender to Artist');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $artists = $manager->getRepository('AppBundle:Artist')->findAll();
        $counter = 0;
        foreach ($artists as $artist) {
            $name = mb_strtolower($artist->getName());
            $kyzy = 'кызы';
            $ova = 'ова';
            $eva = 'ева';
            $uulu = 'уулу';
            $ov = 'ов';
            $ev = 'ев';

            $fPos = strpos_array($name, array($kyzy, $ova, $eva));
            $mPos = strpos_array($name, array($uulu));

            if ($fPos) {
                $artist->setGender('f');
                $manager->persist($artist);
                $manager->flush();
                continue;
            }
            if ($mPos) {
                $artist->setGender('m');
                $manager->persist($artist);
                $manager->flush();
                continue;
            }
            $end = mb_substr($name, -2);
            if (in_array($end, array($ov, $ev))) {
                $artist->setGender('m');
                $manager->persist($artist);
                $manager->flush();
                continue;
            }
            dump($name);
            $counter++;
        }

        $output->writeln($counter . " Artists without genders");
    }
}

function strpos_array($haystack, $needles)
{
    if (is_array($needles)) {
        foreach ($needles as $str) {
            if (is_array($str)) {
                $pos = strpos_array($haystack, $str);
            } else {
                $pos = strpos($haystack, $str);
            }
            if ($pos !== FALSE) {
                return $pos;
            }
        }
    } else {
        return strpos($haystack, $needles);
    }
}
