<?php

namespace App\Command;

use App\Entity\ReservationB;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckExpiredReservationsCommand extends Command
{
    protected static $defaultName = 'app:check-expired-reservations';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Check and process expired reservations.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $currentDateTime = new \DateTime();

        $expiredReservations = $this->entityManager->getRepository(ReservationB::class)
            ->createQueryBuilder('r')
            ->where('r.dateFin <= :currentDate')
            ->andWhere('r.heureFin < :currentTime')
            ->setParameter('currentDate', $currentDateTime->format('Y-m-d'))
            ->setParameter('currentTime', $currentDateTime->format('H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($expiredReservations as $reservation) {
            $borne = $reservation->getBorne();

            if ($borne) {
                $borne->setEtat('Disponible'); // Update the state of the associated Borne entity
            }

            $this->entityManager->remove($reservation); // Remove the expired reservation
        }

        $this->entityManager->flush(); // Persist changes to database

        $output->writeln('Expired reservations processed successfully.');

        return Command::SUCCESS;
    }
}
