<?php

namespace App\Repository;

use App\Entity\ServiceApreslocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServiceApreslocation>
 *
 * @method ServiceApreslocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceApreslocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceApreslocation[]    findAll()
 * @method ServiceApreslocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceApreslocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceApreslocation::class);
    }

//    /**
//     * @return ServiceApreslocation[] Returns an array of ServiceApreslocation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ServiceApreslocation
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
