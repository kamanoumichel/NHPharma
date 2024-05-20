<?php

namespace App\Repository;

use App\Entity\ImagesPro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImagesPro>
 *
 * @method ImagesPro|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesPro|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesPro[]    findAll()
 * @method ImagesPro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesProRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesPro::class);
    }

//    /**
//     * @return ImagesPro[] Returns an array of ImagesPro objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImagesPro
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
