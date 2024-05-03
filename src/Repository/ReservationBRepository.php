<?php

namespace App\Repository;

use App\Entity\ReservationB;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationB>
 *
 * @method ReservationB|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationB|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationB[]    findAll()
 * @method ReservationB[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationBRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationB::class);
    }

//    /**
//     * @return ReservationB[] Returns an array of ReservationB objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReservationB
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
