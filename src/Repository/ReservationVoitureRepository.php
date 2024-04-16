<?php

namespace App\Repository;

use App\Entity\ReservationVoiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationVoiture>
 *
 * @method ReservationVoiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationVoiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationVoiture[]    findAll()
 * @method ReservationVoiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationVoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationVoiture::class);
    }

//    /**
//     * @return ReservationVoiture[] Returns an array of ReservationVoiture objects
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

//    public function findOneBySomeField($value): ?ReservationVoiture
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
