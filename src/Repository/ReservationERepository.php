<?php

namespace App\Repository;

use App\Entity\ReservationE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationE>
 *
 * @method ReservationE|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationE|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationE[]    findAll()
 * @method ReservationE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationE::class);
    }

//    /**
//     * @return ReservationE[] Returns an array of ReservationE objects
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

//    public function findOneBySomeField($value): ?ReservationE
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
