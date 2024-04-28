<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Google\Client as GoogleClient;
use Google\Service\Drive;

// Initialiser le client Google

// Initialiser le client Google
$client = new GoogleClient();
$client->setApplicationName('VOLTRIDE_SYM');
$client->setClientId(''); // Remplacez VOTRE_CLIENT_ID par votre identifiant client
$client->setClientSecret(''); // Remplacez VOTRE_CLIENT_SECRET par votre client secret
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