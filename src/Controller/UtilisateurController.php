<?php

namespace App\Controller;
use Symfony\Component\Form\FormError;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Security\CustomPasswordEncoder; // Importez la classe CustomPasswordEncoder
use App\Service\UserExcelExporter;


#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    private $passwordEncoder;
    private $userExcelExporter; // Ajoutez une propriété pour le service UserExcelExporter

    public function __construct(CustomPasswordEncoder $passwordEncoder, UserExcelExporter $userExcelExporter)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userExcelExporter = $userExcelExporter; // Injectez le service UserExcelExporter
    }

    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        
        // Assigner la date d'inscription actuelle à l'utilisateur
        $utilisateur->setDateInscription(new \DateTime());
        
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe brut depuis le formulaire
            $plainPassword = $form->get('motDePasse')->getData();

            // Crypter le mot de passe
            $encodedPassword = $this->passwordEncoder->encodePassword($plainPassword, null);

            // Stocker le mot de passe crypté dans l'entité Utilisateur
            $utilisateur->setMotDePasse($encodedPassword);
            
            // Récupérer le fichier image soumis dans le formulaire
            $imageFile = $form->get('image')->getData();
            
            // Vérifier si un fichier a été soumis
            if ($imageFile instanceof UploadedFile) {
                // Obtenir le chemin absolu du fichier
                $imageAbsolutePath = $imageFile->getPathname();
                
                // Stocker le chemin absolu de l'image dans l'entité Utilisateur
                $utilisateur->setImage($imageAbsolutePath);
            }
    
            // Enregistrer l'utilisateur en base de données
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            
            // Exporter les utilisateurs vers Excel après chaque ajout
            $this->userExcelExporter->exportUsersToExcel();

            // Rediriger vers la page d'index des utilisateurs
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        // Afficher le formulaire de création d'utilisateur
        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    
    
    

    #[Route('/{id_u}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }




    
    #[Route('/{id_u}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager, CustomPasswordEncoder $passwordEncoder): Response
{
    // Déchiffrer le mot de passe
    $motDePasseDechiffre = $passwordEncoder->decrypt($utilisateur->getMotDePasse());

    // Créer le formulaire en passant les données déchiffrées
    $form = $this->createForm(UtilisateurType::class, $utilisateur);
    $form->get('motDePasse')->setData($motDePasseDechiffre);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier image soumis dans le formulaire
        $imageFile = $form->get('image')->getData();
        
        // Vérifier si un fichier a été soumis
        if ($imageFile instanceof UploadedFile) {
            // Obtenir le chemin absolu du fichier
            $imageAbsolutePath = $imageFile->getPathname();
            
            // Mettre à jour le chemin absolu de l'image dans l'entité Utilisateur
            $utilisateur->setImage($imageAbsolutePath);
        }

        // Enregistrer les modifications de l'utilisateur en base de données
        $entityManager->flush();

        // Rediriger vers la page d'index des utilisateurs
        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    // Afficher le formulaire d'édition de l'utilisateur
    return $this->renderForm('utilisateur/edit.html.twig', [
        'utilisateur' => $utilisateur,
        'form' => $form,
    ]);
}

    
    
    

    

    

    
    

    #[Route('/{id_u}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId_u(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
