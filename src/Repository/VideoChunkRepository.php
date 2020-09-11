<?php

namespace App\Repository;

use App\Entity\VideoChunk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VideoChunk|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoChunk|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoChunk[]    findAll()
 * @method VideoChunk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoChunkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoChunk::class);
    }

    // /**
    //  * @return VideoChunk[] Returns an array of VideoChunk objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideoChunk
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
