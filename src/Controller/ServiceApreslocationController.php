<?php

namespace App\Controller;

use App\Entity\ServiceApreslocation;
use App\Form\ServiceApreslocationType;
use App\Repository\ServiceApreslocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Twilio\Rest\Client;
use App\Entity\Participation; // Import Participation from App\Entity

#[Route('/service/apreslocation')]
class ServiceApreslocationController extends AbstractController
{
    #[Route('/c', name: 'app_service_apreslocation_index', methods: ['GET'])]
    public function index(ServiceApreslocationRepository $serviceApreslocationRepository): Response
    {
        return $this->render('service_apreslocation/index.html.twig', [
            'service_apreslocations' => $serviceApreslocationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_apreslocation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serviceApreslocation = new ServiceApreslocation();
        $form = $this->createForm(ServiceApreslocationType::class, $serviceApreslocation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serviceApreslocation);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_apreslocation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_apreslocation/new.html.twig', [
            'service_apreslocation' => $serviceApreslocation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idservice}', name: 'app_service_apreslocation_show', methods: ['GET'])]
    public function show(ServiceApreslocation $serviceApreslocation): Response
    {
        return $this->render('service_apreslocation/show.html.twig', [
            'service_apreslocation' => $serviceApreslocation,
        ]);
    }

    #[Route('/{idservice}/edit', name: 'app_service_apreslocation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ServiceApreslocation $serviceApreslocation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceApreslocationType::class, $serviceApreslocation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_service_apreslocation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_apreslocation/edit.html.twig', [
            'service_apreslocation' => $serviceApreslocation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idservice}', name: 'app_service_apreslocation_delete', methods: ['POST'])]
    public function delete(Request $request, int $idservice, EntityManagerInterface $entityManager): Response
    {
        $serviceApreslocation = $entityManager->getRepository(ServiceApreslocation::class)->find($idservice);

        if (!$serviceApreslocation) {
            throw $this->createNotFoundException('ServiceApreslocation not found');
        }

        if ($this->isCsrfTokenValid('delete'.$serviceApreslocation->getIdservice(), $request->request->get('_token'))) {
            $entityManager->remove($serviceApreslocation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_apreslocation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/list', name: 'app_service_apreslocation_list', methods: ['GET'])]
    public function list(ServiceApreslocationRepository $serviceApreslocationRepository): Response
    {
        // Fetch only service après locations with the status "disponible"
        $service_apreslocations = $serviceApreslocationRepository->findBy(['statut' => 'disponible']);

        return $this->render('service_apreslocation/indexfront.html.twig', [
            'service_apreslocations' => $service_apreslocations,
        ]);
    }

    #[Route('/front/{idservice}', name: 'app_service_apreslocation_showfront', methods: ['GET'])]
    public function showfront($idservice, ServiceApreslocationRepository $serviceApreslocationRepository): Response
    {
        // Fetch the service après location corresponding to the ID
        $serviceApreslocation = $serviceApreslocationRepository->find($idservice);

        // Check if the service après location exists
        if (!$serviceApreslocation) {
            throw $this->createNotFoundException('Service après location non trouvé.');
        }

        return $this->render('service_apreslocation/showfront.html.twig', [
            'service_apreslocation' => $serviceApreslocation,
        ]);
    }
    #[Route('/{id}/participate', name: 'app_evenement_participate', methods: ['POST'])]
public function participate(Request $request, ServiceApreslocation $service, EntityManagerInterface $entityManager): Response
{
    // Create a new participationé& ²
    $participation = new Participation();
    $participation->setIds($service->getIdservice()); // Set service ID
    $participation->setNbrDeParticipant($participation->getNbrDeParticipant() + 1); // Increment number of participants
   
    // Persist the participation-0  
    $entityManager->persist($participation);
    $entityManager->flush();

    // Send a QR code to the user
    $qrCodeText = "Service est de type : " . $service->getType() .", Le technicien en charge : " . $service->getTechnicien().
    ", Description : " . $service->getDescription().", Votre coût sera de la valeur : " . $service->getCout() ." Dinars". ".Vous êtes le participant numéro : " . $participation->getNbrDeParticipant();
    $qr_code = Qrcode::create($qrCodeText)
                                ->setSize(600)
                                ->setMargin(40)
                                ->setForegroundColor(new Color(0, 0, 0)) // White foreground color
                                ->setBackgroundColor(new Color(255, 255, 255)); // Black background color
    $writer = new PngWriter;
    $result = $writer->write($qr_code);
    $response = new Response($result->getString());
    $response->headers->set('Content-Type', $result->getMimeType());
    
    // Send SMS notification
    $number = '+21652123400'; // Assuming this is the user's phone number
    $account_id = "AC1e9c37a9629988e509e08158b2af99ef";
    $auth_token = "ebe2e414dd99e9e5176fbd8c02bd12f4";
    $client = new Client($account_id, $auth_token);
    $twilio_number = "+16812026170";

    $client->messages->create(
        $number,
        [
            "from" => $twilio_number,
            "body" => $qrCodeText
        ]
    );

    return $response;
}

}
