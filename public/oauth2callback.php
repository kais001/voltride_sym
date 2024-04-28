<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Google\Service\Drive;
use Google\Client as GoogleClient;
use Google\Service\Drive\DriveFile;


// Initialiser le client Google
$client = new GoogleClient();
$client->setClientId('321041928925-nrbors4kbmdtgaaj6kitck6ppb4gj6av.apps.googleusercontent.com'); // Remplacez VOTRE_CLIENT_ID par votre identifiant client
$client->setClientSecret('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'); // Remplacez VOTRE_CLIENT_SECRET par votre client secret
$client->setRedirectUri('https://127.0.0.1:8000/oauth2callback.php'); // Remplacez example.com par le domaine de votre application
$client->addScope(Drive::DRIVE_FILE);
$client->setAccessType('offline');

// Vérifier si le code d'autorisation est présent dans la requête
if (isset($_GET['code'])) {
    // Échanger le code d'autorisation contre un jeton d'accès
    $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // Récupérer le jeton d'accès
    $accessToken = $client->getAccessToken();

    // Stocker le jeton d'accès dans la session ou la base de données, etc.
    $_SESSION['access_token'] = $accessToken;
    // Créer un objet service Google Drive
$driveService = new Google_Service_Drive($client);

// Spécifier le chemin du fichier à télécharger
$filePath = 'C:\Users\Kais\Desktop\users.xlsx';

// Charger le contenu du fichier
$content = file_get_contents($filePath);

// Créer un objet Google Drive File
$file = new Google_Service_Drive_DriveFile();
$file->setName('users.xlsx'); // Spécifier le nom du fichier sur Google Drive
$file->setParents(['1otJgjOk0145cu1JwgoX-hi3-VWlzXv28']); // Spécifier l'ID du dossier de destination sur Google Drive

// Définir les métadonnées et le contenu du fichier
$createdFile = $driveService->files->create($file, [
    'data' => $content,
    'mimeType' => mime_content_type($filePath),
    'uploadType' => 'multipart'
]);




    // Rediriger vers une page de votre application après l'authentification réussie
    header('Location: https://127.0.0.1:8000/login'); // Remplacez example.com par l'URL de votre application
    exit();
} 
