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
    public function findAllOrderByNbrPersonneAsc()
    {
        return $this->createQueryBuilder('re')
            ->orderBy('re.nbrPersonne', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour trier les réservations par nombre de personnes (nbrPersonne) de manière décroissante
    public function findAllOrderByNbrPersonneDesc()
    {
        return $this->createQueryBuilder('re')
            ->orderBy('re.nbrPersonne', 'DESC')
            ->getQuery()
            ->getResult();
    }
    // Méthode pour trier les réservations par nombre de personnes (nbrPersonne)
   
}