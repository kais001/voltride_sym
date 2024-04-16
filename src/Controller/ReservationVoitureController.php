<?php

namespace App\Controller;
use App\Entity\Voiture;

use App\Entity\ReservationVoiture;
use App\Form\ReservationVoitureType;
use App\Repository\ReservationVoitureRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation/voiture')]
class ReservationVoitureController extends AbstractController
{
    #[Route('/', name: 'app_reservation_voiture_indexi', methods: ['GET'])]
    public function index(ReservationVoitureRepository $reservationVoitureRepository): Response
    {
         // Effectuez la jointure avec la méthode 'createQueryBuilder'
    $query = $reservationVoitureRepository->createQueryBuilder('rv')
    ->leftJoin('rv.voiture', 'v') // Faites la jointure avec l'entité 'Voiture'
    ->getQuery();

// Récupérez les résultats de la requête
$reservationVoitures = $query->getResult();

// Passez les résultats à votre modèle Twig
return $this->render('reservation_voiture/indexi.html.twig', [
    'reservation_voitures' => $reservationVoitures,
]);
    }
    #[Route('/i', name: 'app_reservation_voiture_index', methods: ['GET'])]
public function i(VoitureRepository $voitureRepository): Response
{
    return $this->render('reservation_voiture/index.html.twig', [
        'voitures' => $voitureRepository->findAll(),
    ]);
}


    #[Route('/new/{idV}', name: 'app_reservation_voiture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $idV): Response
    {
        // Récupérer la voiture à partir de l'ID
        $voiture = $entityManager->getRepository(Voiture::class)->find($idV);
        
        // Créer une nouvelle instance de ReservationVoiture et associer la voiture
        $reservationVoiture = new ReservationVoiture();
        $reservationVoiture->setVoiture($voiture);
        
        // Créer le formulaire
        $form = $this->createForm(ReservationVoitureType::class, $reservationVoiture);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationVoiture);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reservation_voiture_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reservation_voiture/new.html.twig', [
            'reservation_voiture' => $reservationVoiture,
            'form' => $form,
        ]);
    }

    #[Route('/{idR}', name: 'app_reservation_voiture_show', methods: ['GET'])]
    public function show(ReservationVoiture $reservationVoiture): Response
    {
        return $this->render('reservation_voiture/show.html.twig', [
            'reservation_voiture' => $reservationVoiture,
        ]);
    }

    #[Route('/{idR}/edit', name: 'app_reservation_voiture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationVoiture $reservationVoiture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationVoitureType::class, $reservationVoiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_voiture/edit.html.twig', [
            'reservation_voiture' => $reservationVoiture,
            'form' => $form,
        ]);
    }

    #[Route('/{idR}', name: 'app_reservation_voiture_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationVoiture $reservationVoiture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationVoiture->getIdR(), $request->request->get('_token'))) {
            $entityManager->remove($reservationVoiture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_voiture_index', [], Response::HTTP_SEE_OTHER);
    }
}
