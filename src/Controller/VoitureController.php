<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use App\Repository\ReservationVoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/voiture')]
class VoitureController extends AbstractController
{
    #[Route('/', name: 'app_voiture_index', methods: ['GET'])]
    public function index(VoitureRepository $voitureRepository): Response
    {
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitureRepository->findAll(),
        ]);
    }
    #[Route('/o', name: 'app_reservation_voiture_indexo', methods: ['GET'])]
    public function indexo(ReservationVoitureRepository $reservationVoitureRepository): Response
    {
         // Effectuez la jointure avec la méthode 'createQueryBuilder'
    $query = $reservationVoitureRepository->createQueryBuilder('rv')
    ->leftJoin('rv.voiture', 'v') // Faites la jointure avec l'entité 'Voiture'
    ->getQuery();

// Récupérez les résultats de la requête
$reservationVoitures = $query->getResult();

// Passez les résultats à votre modèle Twig
return $this->render('voiture/afficherReservation.html.twig', [
    'reservation_voitures' => $reservationVoitures,
]);
    }
    private function handleImageUpload(Request $request, Voiture $voiture): void
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('voiture')['image'];

        if ($uploadedFile) {
            $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();

            try {
                $uploadedFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'erreur de téléchargement ici
            }

            $voiture->setImage($newFilename);
        }
    }
    #[Route('/new', name: 'app_voiture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement de l'image
            $this->handleImageUpload($request, $voiture);

            $entityManager->persist($voiture);
            $entityManager->flush();

            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/new.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

   /* #[Route('/{idV}', name: 'app_voiture_show', methods: ['GET'])]
    public function show(Voiture $voiture): Response
    {
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }*/

    #[Route('/{idV}/edit', name: 'app_voiture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voiture $voiture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/edit.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/{idV}', name: 'app_voiture_delete', methods: ['POST'])]
    public function delete(Request $request, Voiture $voiture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voiture->getIdV(), $request->request->get('_token'))) {
            $entityManager->remove($voiture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/statistiques', name: 'app_reservation_statistiques')]
    public function statistiques(VoitureRepository $voitureRepository): Response
    {
        // Utiliser la fonction countReservationsByBrand du repository pour obtenir les statistiques
        $voituresByCarBrand = $voitureRepository->countReservationsByBrand();
    
        // Passer les statistiques à la vue
        return $this->render('voiture/statistiques.html.twig', [
            'voituresByCarBrand' => $voituresByCarBrand,
        ]);
    }
}
