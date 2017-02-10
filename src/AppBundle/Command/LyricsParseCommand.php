<?php

namespace AppBundle\Command;

use LyricsBundle\Entity\Artist;
use LyricsBundle\Entity\Song;
use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LyricsParseCommand extends ContainerAwareCommand
{
    const BASE_URL = 'http://texti-pesen.ucoz.ru';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:lyrics_parse')
            ->setDescription('Parses Lyrics from http://texti-pesen.ucoz.ru/');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        for ($index = 2; $index < 215; $index++) {
            $url = self::BASE_URL . '/publ/pesni_na_kyrgyzskom/1-' . $index;
            $dom = HtmlDomParser::file_get_html($url);
            $output->writeln($url);
            foreach ($dom->find('.eTitle a') as $link) {
                $domText = HtmlDomParser::file_get_html(self::BASE_URL . $link->href);

                $title = $this->extractTitle($domText->find('.eTitle')[0]);
                $name = $this->extractSongName($title);
                $artistName = $this->extractArtistName($title);
                $content = $this->extractContent($domText->find('.eText')[0]->innertext());
                if (strlen($name) > 0 && strlen($artistName) > 0 && strlen($content) > 1) {
                    $output->writeln($title);

                    $artist = $this->getArtist($artistName);
                    if (!$this->songAlreadyExists($artist, $name)) {
                        $song = new Song();
                        $song->setArtist($artist);
                        $song->setName($name);
                        $song->setContent($content);
                        $manager->persist($song);
                        $manager->flush();
                    }
                }
            }
        }
    }

    /**
     * @param $title
     * @return mixed|string
     */
    protected function extractSongName($title)
    {
        $name = '';
        if (substr_count($title, '"') == 2) {
            $name = substr($title, strpos($title, '"'), strrpos($title, '"'));
        } elseif (strpos($title, '-') > 1 && substr_count($title, '-') == 1) {
            $name = substr($title, strpos($title, '-') + 1);

        }
        $name = str_replace('"', '', $name);
        return trim($name);
    }

    protected function extractArtistName($title)
    {
        $name = '';
        if (substr_count($title, '"') == 2) {
            $name = substr($title, 0, strpos($title, '"'));
        } elseif (substr_count($title, '-') == 1) {
            $name = substr($title, 0, strpos($title, '-') + 1);
        }
        $name = str_replace('"', '', $name);
        $name = str_replace('-', '', $name);
        return trim($name);
    }

    /**
     * @param $content
     * @return mixed|string
     */
    protected function extractContent($content)
    {
        $content = htmlspecialchars_decode($content);
        $content = str_replace('<div id="nativeroll_video_cont" style="display:none;"></div>', '', $content);
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
        $content = preg_replace('/<p>Скачать эту песню(.*?)<\/p>/is', '', $content);
        return trim($content);
    }

    private function extractTitle($title)
    {
        return htmlspecialchars_decode(strip_tags($title));
    }

    private function getArtist($artistName)
    {
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $artist = $manager->getRepository('LyricsBundle:Artist')->findOneBy(array('name' => $artistName));
        if (!$artist instanceof Artist) {
            $artist = new Artist();
            $artist->setName($artistName);
            $manager->persist($artist);
            $manager->flush();
            $this->getArtist($artistName);
        }

        return $artist;

    }

    private function songAlreadyExists($artist, $name)
    {
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $song = $manager->getRepository('LyricsBundle:Song')
            ->findOneBy(array('artist' => $artist, 'name' => $name));
        return $song instanceof Song;
    }
}
