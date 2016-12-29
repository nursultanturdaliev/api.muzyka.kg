<?php
namespace  AppBundle\Services;

/**
 * Created by PhpStorm.
 * User: adilet
 * Date: 12/29/16
 * Time: 4:19 PM
 */
use Doctrine\ORM\EntityManager;

class SongService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getAllSongs()
    {
        $entities = $this->em->getRepository('AppBundle:Song')
            ->createQueryBuilder('s')
            ->select('s.id', 's.artist', 's.title', 's.duration', 's.path')
            ->where('s.path IS NOT NULL')
            ->getQuery()
            ->getResult();
        return $entities;
    }

    public function getSongs()
    {
        $entities = $this->em->getRepository('AppBundle:Song')
            ->createQueryBuilder('s')
            ->select('s.id', 's.artist', 's.title', 's.duration')
            ->where('s.path IS NOT NULL')
            ->getQuery()
            ->getResult();
        return $entities;
    }

    public function getSong($id)
    {
        $entity = $this->em->getRepository('AppBundle:Song')
            ->createQueryBuilder('s')
            ->select('s.artist', 's.title', 's.path')
            ->where('s.path IS NOT NULL')
            ->andWhere('s.id =:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        return $entity;
    }

}