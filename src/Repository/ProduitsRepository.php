<?php

namespace App\Repository;

use App\Entity\Produits;
use App\Service\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Produits>
 *
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }

//    /**
//     * @return Produits[] Returns an array of Produits objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
public function PaginationQuery()
{
    return $this->createQueryBuilder('a')
        ->orderBy('a.id', 'DESC')
        ->getQuery()
        
    ;
}
//    public function findOneBySomeField($value): ?Produits
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
/*public function findBySearch(SearchData $searchData): PaginationInterface
{
    $data = $this->createQueryBuilder('p')
        ->where('p.nom_pro LIKE :state')
        ->addOrderBy('p.id', 'DESC')
        ->setParameter('state', '%STATE_PUBLISHED%');

    if (!empty($searchData->q)) {
        $data = $data
            ->andWhere('p.nom_pro LIKE :q')
            ->setParameter('q',"%{$searchData->q}%");
    }
    $data = $data
        ->getQuery()
        ->getResult();

    
    $search = $this->PaginatorInterface->paginate($data, $searchData->pager,3);
    return $search;
}*/
}
