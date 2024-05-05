<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Utilisateur;
use App\Entity\ReservationE;
use App\Form\ReservationE1Type;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationERepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailSender; // Import the EmailSender service
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/reservation/e')]
class ReservationEController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    
    #[Route('/', name: 'app_reservation_e_index', methods: ['GET'])]
    public function index(ReservationERepository $reservationERepository, Request $request): Response
    {
        // Calculate statistics
        $totalReservations = $reservationERepository->getTotalReservations();
        $totalPersons = $reservationERepository->getTotalPersons();
        $reservationEs = $reservationERepository->findAll();
          // Récupérer les réservations triées par nombre de personnes croissante
    $reservationEAscending = $reservationERepository->findAllOrderByNbrPersonneAsc();

    // Récupérer les réservations triées par nombre de personnes décroissante
    $reservationEDescending = $reservationERepository->findAllOrderByNbrPersonneDesc();
             // Effectuez la jointure avec la méthode 'createQueryBuilder'
        $query = $reservationERepository->createQueryBuilder('re')
        ->leftJoin('re.evenement', 'e') // Faites la jointure avec l'entité '
        ->getQuery();
        
    // Récupérez les résultats de la requête
    $reservationE = $query->getResult();
   
    // Passez les résultats à votre modèle Twig
    return $this->render('reservation_e/index.html.twig', [
        'reservation_e' => $reservationE,
        'reservation_e_ascending' => $reservationEAscending,
        'reservation_e_descending' => $reservationEDescending,
        'totalReservations' => $totalReservations,
            'totalPersons' => $totalPersons,
            'reservation_es' => $reservationEs,
            
    ]);
    
        }
        #[Route('/i', name: 'app_reservation_e_indexi', methods: ['GET'])]
        public function i(EvenementRepository $EvenementRepository): Response
        {
            // Fetch all 'Borne' entities
            $evenements = $EvenementRepository->findAll();
    
            // Render the index template with 'Borne' entities
            return $this->render('reservation_e/indexi.html.twig', [
                'evenements' => $evenements,
            ]);
        }

        #[Route('/new/{id_event}', name: 'app_reservation_e_new', methods: ['GET', 'POST'])]
        public function new(Request $request, EntityManagerInterface $entityManager, int $id_event,  EmailSender $emailSender): Response
        {
            // Récupérer l'ID de l'utilisateur connecté à partir de la session
            $userId = $this->session->get('user_id');
        
            // Vérifier si l'utilisateur est connecté
            if (!$userId) {
                throw $this->createAccessDeniedException('Vous devez être connecté pour effectuer cette action.');
            }
        
            // Récupérer l'utilisateur à partir de son ID
            $user = $entityManager->getRepository(Utilisateur::class)->find($userId);
        
            // Vérifier si l'utilisateur existe
            if (!$user) {
                throw $this->createNotFoundException('L\'utilisateur n\'existe pas.');
            }
        
            // Récupérer l'événement à partir de l'ID
            $evenement = $entityManager->getRepository(Evenement::class)->find($id_event);
        
            // Vérifier si l'événement existe
            if (!$evenement) {
                throw $this->createNotFoundException('L\'événement n\'existe pas.');
            }
        
            // Créer une nouvelle instance de ReservationE et associer l'utilisateur et l'événement
            $reservationE = new ReservationE();
            $reservationE->setUtilisateur($user); // Associer l'utilisateur connecté
            $reservationE->setEvenement($evenement); // Associer l'événement sélectionné
        
            // Créer le formulaire
            $form = $this->createForm(ReservationE1Type::class, $reservationE);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($reservationE);
                $entityManager->flush();
        
                // Envoyer un e-mail de confirmation
                // Send email
            $recipientEmail = 'voltridetunisia@gmail.com'; // Remplacez par votre adresse e-mail
            $subject = 'Bienvenue sur Voltride';
            $message = 'Un grand merci pour votre réservation chez nous ! Nous sommes impatients de vous accueillir et de vous offrir une expérience inoubliable.';
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
