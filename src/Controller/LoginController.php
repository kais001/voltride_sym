<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Security\CustomPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface; 

class LoginController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Message par défaut
        $message = '';

        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');

            // Vérifier si l'utilisateur existe dans la base de données
            $user = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);

            if (!$user) {
                // L'utilisateur n'existe pas
                $error = 'Email incorrect.';
                $message = 'email ghalet';
            } else {
                // Afficher les valeurs pour débogage
                dump($password, $user->getMotDePasse());
            
                // Décrypter le mot de passe stocké dans la base de données
                $decryptedPassword = CustomPasswordEncoder::decrypt($user->getMotDePasse());
            
                // Vérifier si le mot de passe est correct
                if ($password === $decryptedPassword) {
                    // Authentification réussie
                    $message = 'mriguel';
                } else {
                    // Mot de passe incorrect
                    $error = 'Mot de passe incorrect.';
                    // Afficher le mot de passe de l'utilisateur
                    $message = ' mot de passe ghalet';
                }
            }
        }

        return $this->render('login/log.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
            'message'       => $message,
        ]);
    }

    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('baseF.html.twig');
    }
}

