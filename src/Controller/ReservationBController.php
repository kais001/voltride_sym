<?php

namespace App\Controller;

use App\Entity\Borne;
use Twilio\Rest\Client;
use App\Entity\Utilisateur;
use Twilio\Http\CurlClient;
use App\Entity\ReservationB;
use App\Form\ReservationB1Type;
use App\Repository\BorneRepository;
use Twilio\Exceptions\TwilioException;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationBRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/reservation/b')]
class ReservationBController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
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
        $borne = $entityManager->getRepository(Borne::class)->find($id);
    
        $reservationB = new ReservationB();
        $reservationB->setBorne($borne);
        $reservationB->setUtilisateur($user); 
    
        $form = $this->createForm(ReservationB1Type::class, $reservationB);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
          
                // Persist the reservation
                $borne->setEtat('Indisponible');
                $entityManager->persist($reservationB);
                $entityManager->flush();
    
                // Retrieve Twilio credentials and phone number from environment variables
                $twilioAccountSid = 'ACc51bccccd69cb574e2b382ec326f8651';
                $twilioAuthToken = '0c5148d225b4f58fabfe2be3f378daa2';
                $twilioPhoneNumber = '+15855222234';
                $recipientPhoneNumber = '+21655695969'; // Replace with recipient's phone number
                $message = 'Votre réservation (ID: ' . $reservationB->getIdR() . ')' .
                ' pour la borne de ' . $reservationB->getBorne()->getEmplacement() .
                ' commence le ' . $reservationB->getDateDebut()->format('Y-m-d ') .
                ' à (' . $reservationB->getHeureDebut()->format('H:i') . ')' .
                ' et se termine le ' . $reservationB->getDateFin()->format('Y-m-d ') .
                ' à (' . $reservationB->getHeureFin()->format('H:i') . ')' .
                ' a été effectuée avec succés';

               // CurlClient instance with SSL verification Disabled    
               $httpClient = new CurlClient([CURLOPT_SSL_VERIFYPEER => false]);
               
                // Initialize Twilio client
                $client = new Client($twilioAccountSid, $twilioAuthToken);
                $client->setHttpClient($httpClient);
    
                // Send SMS message
                $client->messages->create(
                    $recipientPhoneNumber,
                    [
                        'from' => $twilioPhoneNumber,
                        'body' => $message,
                    ]
                );
                // Flash message indicating success
                $this->addFlash('success', 'Reservation saved and SMS sent successfully!');
      
            // Redirect to the index route after successful save
            return $this->redirectToRoute('app_reservation_borne_index', [], Response::HTTP_SEE_OTHER);
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
            $borne = $reservationB->getBorne();

            // Mark the Borne as disponible
            $borne->setEtat('Disponible');
            $entityManager->remove($reservationB);
            $entityManager->flush();
            
        }

        return $this->redirectToRoute('app_reservation_borne_index', [], Response::HTTP_SEE_OTHER);
    }
}
