<?php

namespace Dig\ApiBundle\Repository;

/**
 * ImageRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImageRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param int $albumId
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAlbumWithImagesSearchQuery($albumId)
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i')
            ->leftJoin('i.album', 'a')
            ->where('a.id = :albumId')
            ->setParameter('albumId', $albumId);

        return $qb->getQuery();
    }
}
