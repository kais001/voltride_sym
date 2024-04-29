<?php

namespace App\Controller;

use App\Entity\Borne;
use App\Entity\ReservationB;
use App\Form\ReservationB1Type;
use App\Repository\BorneRepository;
use App\Repository\ReservationBRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;


#[Route('/reservation/b')]
class ReservationBController extends AbstractController
{


    #[Route('/', name: 'app_reservation_b_indexi', methods: ['GET'])]
    public function index(ReservationBRepository $reservationBRepository): Response
    {
        // Fetch reservation data with a join query
        $query = $reservationBRepository->createQueryBuilder('rb')
            ->leftJoin('rb.borne', 'b')
            ->getQuery();

        // Get results from the query
        $reservationBornes = $query->getResult();

        // Render the index template with reservation data
        return $this->render('reservation_b/indexi.html.twig', [
            'reservation_bs' => $reservationBornes,
        ]);
    }

    #[Route('/i', name: 'app_reservation_borne_index', methods: ['GET'])]
    public function i(BorneRepository $borneRepository): Response
    {
        // Fetch all 'Borne' entities
        $bornes = $borneRepository->findAll();

        // Render the index template with 'Borne' entities
        return $this->render('reservation_b/index.html.twig', [
            'bornes' => $bornes,
        ]);
    }

    #[Route('/new/{id}', name: 'app_reservation_borne_new', methods: ['GET', 'POST'])]
    
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        int $id
    ): Response {
        $borne = $entityManager->getRepository(Borne::class)->find($id);
    
        $reservationB = new ReservationB();
        $reservationB->setBorne($borne);
    
        $form = $this->createForm(ReservationB1Type::class, $reservationB);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
                // Persist the reservation
                $entityManager->persist($reservationB);
                $entityManager->flush();
    

    
            // Redirect to the index route after successful save
            return $this->redirectToRoute('app_reservation_b_indexi', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reservation_b/new.html.twig', [
            'reservation_borne' => $reservationB,
            'form' => $form,
        ]);
    }

    #[Route('/{idR}', name: 'app_reservation_b_show', methods: ['GET'])]
    public function show(ReservationB $reservationB): Response
    {
        return $this->render('reservation_b/show.html.twig', [
            'reservation_b' => $reservationB,
        ]);
    }

    #[Route('/{idR}/edit', name: 'app_reservation_b_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationB $reservationB, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationB1Type::class, $reservationB);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_b_indexi', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_b/edit.html.twig', [
            'reservation_b' => $reservationB,
            'form' => $form,
        ]);
    }

    #[Route('/{idR}', name: 'app_reservation_b_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationB $reservationB, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservationB->getIdR(), $request->request->get('_token'))) {
            $entityManager->remove($reservationB);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_b_indexi', [], Response::HTTP_SEE_OTHER);
    }
}
