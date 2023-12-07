<?php

namespace App\Repository;

use App\Entity\ChambresReservees;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChambresReservees>
 *
 * @method ChambresReservees|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChambresReservees|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChambresReservees[]    findAll()
 * @method ChambresReservees[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChambresReserveesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChambresReservees::class);
    }

//    /**
//     * @return ChambresReservees[] Returns an array of ChambresReservees objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ChambresReservees
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
