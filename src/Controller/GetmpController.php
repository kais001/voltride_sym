<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Services\EmailSender;
use App\Security\CustomPasswordEncoder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetmpController extends AbstractController
{
    private $entityManager;
    private $emailSender;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, EmailSender $emailSender, CustomPasswordEncoder $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->emailSender = $emailSender;
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/pass', name: 'app_show_password_recovery_form', methods: ['GET', 'POST'])]
    public function getPasswordRecovery(Request $request): Response
    {
        $email = $request->request->get('email');

        // Validation de l'e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Affiche un message d'erreur si l'e-mail n'est pas valide
            $message = 'L\'adresse e-mail fournie n\'est pas valide.';
            return $this->render('login/getmp.html.twig', [
                'message' => $message,
            ]);
        }

        // Vérifie si l'e-mail existe dans la base de données
        $utilisateurRepository = $this->entityManager->getRepository(Utilisateur::class);
        $utilisateur = $utilisateurRepository->findOneBy(['email' => $email]);

        if ($utilisateur) {
            // Génère un nouveau mot de passe sécurisé
            $newPassword = $this->generatePassword();
            
            // Envoie le nouveau mot de passe par e-mail
            try {
                $subject = 'Nouveau mot de passe';
                $message = 'Votre nouveau mot de passe est : ' . $newPassword;
                $this->emailSender->sendEmail($email, $subject, $message);
            } catch (\Exception $e) {
                // Gérer les erreurs d'envoi de courrier électronique
                $message = 'Une erreur est survenue lors de l\'envoi du courrier électronique. Veuillez réessayer plus tard.';
                return $this->render('login/getmp.html.twig', [
                    'message' => $message,
                ]);
            }
            
            // Crypte le nouveau mot de passe
            $encodedPassword = $this->passwordEncoder->encodePassword($newPassword, null);
            
            // Met à jour le mot de passe dans la base de données
            $utilisateur->setMotDePasse($encodedPassword);
            $this->entityManager->flush();

            // Affiche un message de succès
            $message = 'Un nouveau mot de passe a été envoyé à votre adresse e-mail.';
        } else {
            // Affiche un message d'erreur si l'e-mail n'existe pas dans la base de données
            $message = 'L\'adresse e-mail fournie n\'existe pas dans notre base de données.';
        }

        return $this->render('login/getmp.html.twig', [
            'message' => $message,
        ]);
    }

    // Méthode pour générer un nouveau mot de passe sécurisé
    private function generatePassword(int $length = 12): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_';
        $password = '';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        return $password;
    }
}
