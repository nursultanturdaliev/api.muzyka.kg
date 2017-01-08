<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SongRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SongRepository extends EntityRepository
{
    public function findAllQuery()
    {
        return $this->createQueryBuilder('s');
    }

    public function getInfo()
    {
        return array(
            'count' => $this->createQueryBuilder('song')
                ->select('count(song.id)')
                ->getQuery()
                ->getSingleScalarResult()
        );
    }
}
