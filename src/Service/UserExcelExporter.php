<?php

namespace App\Service;
require_once __DIR__ . '/../../vendor/autoload.php';

use Google\Service\Drive;
use Google\Client as GoogleClient;
use Google\Service\Drive\DriveFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet; // Importez la classe Spreadsheet
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class UserExcelExporter
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function exportUsersToExcel()
    {
        // Récupère tous les utilisateurs depuis la base de données
        $users = $this->entityManager->getRepository(Utilisateur::class)->findAll();

        // Crée un nouvel objet Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // En-têtes de colonne
        $sheet->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Nom')
            ->setCellValue('C1', 'Prénom')
            ->setCellValue('D1', 'Email')
            ->setCellValue('E1', 'Mot de passe')
            ->setCellValue('F1', 'Date d\'inscription');

        // Remplissage des données des utilisateurs
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->getId_u())
                ->setCellValue('B' . $row, $user->getNom())
                ->setCellValue('C' . $row, $user->getPrenom())
                ->setCellValue('D' . $row, $user->getEmail())
                ->setCellValue('E' . $row, $user->getMotDePasse())
                ->setCellValue('F' . $row, $user->getDateInscription()->format('Y-m-d'));
            $row++;
        }

        // Spécifiez le chemin absolu où vous souhaitez enregistrer le fichier Excel
        $filePath = 'C:\Users\Kais\Desktop\users.xlsx';

        // Enregistre le fichier Excel
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        
// Initialiser le client Google
$client = new GoogleClient();
$client->setApplicationName('VOLTRIDE_SYM');
$client->setClientId('321041928925-nrbors4kbmdtgaaj6kitck6ppb4gj6av.apps.googleusercontent.com'); // Remplacez VOTRE_CLIENT_ID par votre identifiant client
$client->setClientSecret('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'); // Remplacez VOTRE_CLIENT_SECRET par votre client secret
$client->addScope(Drive::DRIVE_FILE);
$client->setAccessType('offline');
$client->setRedirectUri('https://127.0.0.1:8000/oauth2callback.php'); // Assurez-vous de remplacer par l'URI de redirection correcte

// Vérifier si le code d'autorisation est présent dans la requête
if (isset($_GET['code'])) {
    // Échanger le code d'autorisation contre un jeton d'accès
    $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // Récupérer le jeton d'accès
    $accessToken = $client->getAccessToken();

    // Stocker le jeton d'accès dans la session ou la base de données, etc.
    $_SESSION['access_token'] = $accessToken;

    // Rediriger vers une page de votre application après l'authentification réussie
    header('Location: https://127.0.0.1:8000/oauth2callback.php'); // Remplacez par l'URL de votre application
    exit();
} else {
    // Si le code d'autorisation n'est pas présent, rediriger vers l'URL d'autorisation OAuth
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}
        
    }
}