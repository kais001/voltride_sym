<?php

namespace App\Controller;

use App\Entity\ReservationE;
use App\Form\ReservationE1Type;
use App\Repository\ReservationERepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailSender; // Import the EmailSender service

#[Route('/reservation/e')]
class ReservationEController extends AbstractController
{
    #[Route('/', name: 'app_reservation_e_index', methods: ['GET'])]
    public function index(ReservationERepository $reservationERepository, Request $request): Response
    {
          // Récupérer les réservations triées par nombre de personnes croissante
    $reservationEAscending = $reservationERepository->findAllOrderByNbrPersonneAsc();

    // Récupérer les réservations triées par nombre de personnes décroissante
    $reservationEDescending = $reservationERepository->findAllOrderByNbrPersonneDesc();
             // Effectuez la jointure avec la méthode 'createQueryBuilder'
        $query = $reservationERepository->createQueryBuilder('re')
        ->leftJoin('re.evenement', 'e') // Faites la jointure avec l'entité 'Voiture'
        ->getQuery();
        
    // Récupérez les résultats de la requête
    $reservationE = $query->getResult();
   
    // Passez les résultats à votre modèle Twig
    return $this->render('reservation_e/index.html.twig', [
        'reservation_e' => $reservationE,
        'reservation_e_ascending' => $reservationEAscending,
        'reservation_e_descending' => $reservationEDescending,
    ]);
    
        }
    #[Route('/new', name: 'app_reservation_e_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EmailSender $emailSender): Response
    {
        $reservationE = new ReservationE();
        $form = $this->createForm(ReservationE1Type::class, $reservationE);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationE);
            $entityManager->flush();

            // Send email
            $recipientEmail = 'voltridetunisia@gmail.com'; // Replace with your email address
            $subject = 'New Reservation';
            $message = 'A new reservation has been made Thank you.';

            $emailSender->sendEmail($recipientEmail, $subject, $message);

            return $this->redirectToRoute('app_reservation_e_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_e/new.html.twig', [
            'reservation_e' => $reservationE,
            'form' => $form,
        ]);
    }

    #[Route('/{id_e}', name: 'app_reservation_e_show', methods: ['GET'])]
    public function show(ReservationE $reservationE): Response
    {
        return $this->render('reservation_e/show.html.twig', [
            'reservation_e' => $reservationE,
        ]);
    }

    #[Route('/{id_e}/edit', name: 'app_reservation_e_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationE $reservationE, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationE1Type::class, $reservationE);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_e_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_e/edit.html.twig', [
            'reservation_e' => $reservationE,
            'form' => $form,
        ]);
    }

    #[Route('/{id_e}', name: 'app_reservation_e_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationE $reservationE, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationE->getId_e(), $request->request->get('_token'))) {
            $entityManager->remove($reservationE);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_e_index', [], Response::HTTP_SEE_OTHER);
    }
}
