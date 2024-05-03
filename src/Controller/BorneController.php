<?php

namespace App\Controller;

use App\Entity\Borne;
use App\Form\BorneType;
use App\Repository\BorneRepository;
use App\Repository\ReservationBRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Twilio\Exceptions\ConfigurationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Dompdf;


#[Route('/borne')]
class BorneController extends AbstractController
{
    #[Route('/', name: 'app_borne_index', methods: ['GET'])]
    public function index(BorneRepository $borneRepository): Response
    {
        return $this->render('borne/index.html.twig', [
            'bornes' => $borneRepository->findAll(),
        ]);
    }
    #[Route('/r', name: 'app_borne_indexo', methods: ['GET'])]
    public function indexo(ReservationBRepository $reservationBRepository): Response
    {
         // Effectuez la jointure avec la méthode 'createQueryBuilder'
         $query = $reservationBRepository->createQueryBuilder('rb')
         ->leftJoin('rb.borne', 'b') // Faites la jointure avec l'entité 'Voiture'
         ->getQuery();
     
     // Récupérez les résultats de la requête
     $reservationBornes = $query->getResult();
     
     // Passez les résultats à votre modèle Twig
     return $this->render('borne/afficherResB.html.twig', [
         'reservation_bs' => $reservationBornes,
     ]);
    }

// BorneController.php

#[Route('/pdf', name: 'generate_pdf')]
public function generatePdf(BorneRepository $borneRepository): Response
{
    // Fetch data for PDF content (e.g., from repository)
    $bornes = $borneRepository->findAll();

    // Create PDF using Dompdf library
    $dompdf = new Dompdf();
    $html = $this->renderView('borne/pdf.html.twig', [
        'bornes' => $bornes,
    ]);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    // Stream the generated PDF file as response
    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');

    return $response;
}

#[Route('/statistics', name: 'app_borne_statistics')]
public function statistics(BorneRepository $borneRepository): JsonResponse
{
    // Fetch statistics data from repository or service
    $statistics = $borneRepository->getCapaciteStatistics(); // Example: custom method to retrieve statistics

    // Format data as needed (e.g., labels and dataset)
    $labels = array_column($statistics, 'emplacement');
    $dataset = array_column($statistics, 'capacite');

    // Prepare JSON response
    $response = [
        'labels' => $labels,
        'data' => $dataset,
    ];

    return new JsonResponse($response);
}


    #[Route('/new', name: 'app_borne_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $borne = new Borne();
        $form = $this->createForm(BorneType::class, $borne);
        $form->handleRequest($request);
                    // Retrieve recipient's phone number (for example, from the submitted form)
                    $phoneNumber = '+21655695969'; // Set recipient phone number

                    // Create Twilio client instance using environment variables
                    try {
                        $twilioSid = getenv('TWILIO_ACCOUNT_SID');
                        $twilioToken = getenv('TWILIO_AUTH_TOKEN');
        
                        if (!$twilioSid || !$twilioToken) {
                            throw new ConfigurationException('Twilio credentials are missing or invalid');
                        }
        
                        $twilioClient = new Client($twilioSid, $twilioToken);
        
                        // Compose SMS message
                        $message = 'New Borne created: ' . $borne->getId(); // Customize message as needed
        
                        // Send SMS using Twilio
                        $twilioClient->messages->create(
                            $phoneNumber,
                            [
                                'from' => getenv('TWILIO_PHONE_NUMBER'),
                                'body' => $message,
                            ]
                        );
        
                        $this->addFlash('success', 'Borne created successfully and SMS sent!');
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Failed to send SMS: ' . $e->getMessage());
                    }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($borne);
            $entityManager->flush();



            return $this->redirectToRoute('app_borne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('borne/new.html.twig', [
            'borne' => $borne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_borne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Borne $borne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BorneType::class, $borne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_borne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('borne/edit.html.twig', [
            'borne' => $borne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_borne_delete', methods: ['POST'])]
    public function delete(Request $request, Borne $borne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$borne->getId(), $request->request->get('_token'))) {
            $entityManager->remove($borne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_borne_index', [], Response::HTTP_SEE_OTHER);
    }
}
