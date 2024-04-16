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

#[Route('/reservation/e')]
class ReservationEController extends AbstractController
{
    #[Route('/', name: 'app_reservation_e_index', methods: ['GET'])]
    public function index(ReservationERepository $reservationERepository): Response
    {
        return $this->render('reservation_e/index.html.twig', [
            'reservation_es' => $reservationERepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_e_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationE = new ReservationE();
        $form = $this->createForm(ReservationE1Type::class, $reservationE);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationE);
            $entityManager->flush();

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
