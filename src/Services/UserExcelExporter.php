<?php

namespace App\Service;

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
    }
}
