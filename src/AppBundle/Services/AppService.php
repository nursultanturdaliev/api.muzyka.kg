<?php

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppService
{
    public function slug($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','ң','о','ө','п',
            'р','с','т','у','ү','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','Ө','П',
            'Р','С','Т','У','Ү','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','n','o','o','p',
            'r','s','t','u','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','O','P',
            'R','S','T','U','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
        ];
        $text = str_replace($cyr, $lat, $text);
        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);
        // lowercase
        $text = strtolower($text);

        return $text;
    }
}
