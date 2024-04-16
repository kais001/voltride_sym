<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Utilisateur;
use App\Form\AdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer une nouvelle instance d'utilisateur et d'admin
        $utilisateur = new Utilisateur();
        $admin = new Admin();
        // Associer l'utilisateur à l'admin
        $admin->setUtilisateur($utilisateur);

        // Créer le formulaire en utilisant AdminType
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données de l'utilisateur à partir du formulaire
            $utilisateurData = $form->get('utilisateur')->getData();

            // Mettre à jour les informations de l'utilisateur avec les données du formulaire
            $utilisateur->setNom($utilisateurData->getNom());
            $utilisateur->setPrenom($utilisateurData->getPrenom());
            $utilisateur->setEmail($utilisateurData->getEmail());
            $utilisateur->setMotDePasse($utilisateurData->getMotDePasse());
            $utilisateur->setImage($utilisateurData->getImage());
            $utilisateur->setDateInscription(new \DateTime()); // Utiliser la date universelle actuelle
            // Autres informations utilisateur ...

            // Enregistrer l'utilisateur dans la base de données
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // Récupérer le département à partir du formulaire
            $departement = $form->get('departement')->getData();
            // Mettre à jour le département de l'admin
            $admin->setDepartement($departement);

            // Enregistrer l'admin dans la base de données
            $entityManager->persist($admin);
            $entityManager->flush();

            // Rediriger vers la page d'accueil ou une autre page appropriée après l'enregistrement réussi
            return $this->redirectToRoute('app_admin_index');
        }

        // Afficher le formulaire
        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $admins = $entityManager->getRepository(Admin::class)->findAll();
        
        return $this->render('admin/index.html.twig', [
            'admins' => $admins,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(Admin $admin): Response
    {
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Admin $admin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Admin $admin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$admin->getUtilisateur(), $request->request->get('_token'))) {
            $entityManager->remove($admin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index');
    }
}
