<?php
/**
 * Created by PhpStorm.
 * User: adilet
 * Date: 11/26/16
 * Time: 6:35 PM
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CronTasksRunCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
            ->setName('crontasks:run')
            ->setDescription('Runs Cron Tasks if needed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Running Cron Tasks...</comment>');

        $this->output = $output;
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $song = $em->getRepository('AppBundle:Song')->findOneByPath(null);
        $username = $this->translit($song->getArtist());
        $user = $em->getRepository('ApplicationSonataUserBundle:User')->findOneByUsername($username);

        if (empty($user)){
            $userManipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
            $isActive = true;
            $isSuperAdmin = false;
            $userManipulator->create($username, $username.'pwd', $username.'@mail.com', $isActive, $isSuperAdmin);
            $user = $em->getRepository('ApplicationSonataUserBundle:User')->findOneByUsername($username);
        }
        if($song){
            $file = file_get_contents($song->getUrl());
            $result = null;
            $result = file_put_contents($this->getContainer()->get('kernel')->getRootDir(). '/../web/uploads/musics/'.$song->getArtist().' - '.$song->getTitle().'.mp3', $file);
            if($result){
                $song->setPath('uploads/musics/'.$song->getArtist().' - '.$song->getTitle().'.mp3');
                $song->setSinger($user);
                $song->setUploadDate(new \DateTime("now"));
                $em->persist($song);
                $em->flush();
            }
        }

        $output->writeln('<comment>Done!</comment>');
    }

    private function translit($s) {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('ң'=>'n','ө'=>'o','ү'=>'u','а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "_", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }
}