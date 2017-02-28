<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use LyricsBundle\Entity\Song as LyricsSong;

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

    /**
     * @param LyricsSong $song
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findBySongAndArtist(LyricsSong $song)
    {
        return $this->createQueryBuilder('song')
            ->join('song.artist', 'artist')
            ->where('song.title = :title')
            ->andWhere('artist.name = :artistName')
            ->setParameter('title', $song->getName())
            ->setParameter('artistName', $song->getArtist()->getName())
            ->getQuery()
            ->execute();
    }
}
