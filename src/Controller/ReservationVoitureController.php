<?php

namespace App\Controller;
use App\Entity\Voiture;
use Dompdf\Dompdf;
use App\Entity\ReservationVoiture;
use App\Form\ReservationVoitureType;
use App\Repository\ReservationVoitureRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

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
    public function i(VoitureRepository $voitureRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer le terme de recherche depuis la requête GET
        $searchTerm = $request->query->get('q');
    
        // Créer une requête pour récupérer les voitures disponibles
        $query = $voitureRepository->createQueryBuilder('v')
            ->where('v.etat = :etat')
            ->setParameter('etat', 'disponible');
    
        // Si un terme de recherche est spécifié, filtrer les voitures par marque ou modèle
        if ($searchTerm) {
            $query->andWhere('v.marque LIKE :term OR v.modele LIKE :term')
                ->setParameter('term', '%' . $searchTerm . '%');
        }
    
        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query->getQuery(), // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page par défaut
            5 // Nombre d'éléments par page
        );
    
        // Passer les résultats paginés à votre modèle Twig
        return $this->render('reservation_voiture/index.html.twig', [
            'pagination' => $pagination,
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
            // Mettre à jour l'état de la voiture
            $voiture->setEtat('indisponible');
            
            // Persistez la réservation et la voiture mise à jour
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
            // Récupérer la voiture associée à la réservation
            $voiture = $reservationVoiture->getVoiture();
            
            // Mettre à jour l'état de la voiture à "disponible"
            $voiture->setEtat("disponible");
            
            // Supprimer la réservation
            $entityManager->remove($reservationVoiture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_voiture_index', [], Response::HTTP_SEE_OTHER);
    }
    
    public function downloadPdf(ReservationVoiture $reservationVoiture): Response
    {
        // Générez le HTML pour le contenu du PDF
        $html = $this->renderView('reservation_voiture/pdf_template.html.twig', [
            'reservation_voiture' => $reservationVoiture,
        ]);
    
        // Générez le PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        // Retournez la réponse HTTP avec le PDF en tant que contenu
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="reservation.pdf"'
            ]
        );
    }

}
