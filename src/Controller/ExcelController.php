<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Entity\Evenement;

class ExcelController extends AbstractController
{
    #[Route('/excel', name: 'app_excel')]
    public function generate(Request $request): Response
    {
        // Récupère les données depuis la base de données
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->findAll();

        // Crée une nouvelle instance de la classe Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Sélectionne la feuille active
        $sheet = $spreadsheet->getActiveSheet();

        // Charge le logo depuis le serveur
        $logoPath = $this->getParameter('kernel.project_dir') . '/public/images/voltride.jpg';
        $drawing = new Drawing();
        $drawing->setPath($logoPath);
        $drawing->setCoordinates('B1');

        // Définit les dimensions du logo
        $drawing->setWidth(250); // Largeur du logo en pixels
        $drawing->setHeight(250); // Hauteur du logo en pixels

        // Ajoute le logo à la feuille de calcul
        $drawing->setWorksheet($sheet);

        // Ajustement de la taille des colonnes
        $sheet->getColumnDimension('B')->setWidth(20); // Ajuste la largeur de la colonne B
        $sheet->getColumnDimension('C')->setWidth(20); // Ajuste la largeur de la colonne C
        // Ajoute d'autres ajustements de taille de colonnes si nécessaire

        // Ajustement de la taille des lignes
        $sheet->getRowDimension(15)->setRowHeight(30);

        // Décalage pour la première ligne après le logo
        $rowOffset = 2;

        // Ajoute les en-têtes des colonnes
        $sheet->setCellValue('B15', 'ID Event');
        $sheet->setCellValue('C15', 'Type');
        $sheet->setCellValue('D15', 'Adresse Evenement');
        $sheet->setCellValue('E15', 'Date Evenement');
        $sheet->setCellValue('F15', 'Places Disponibles');

        // Ajoute les données à la feuille de calcul
        $row = 15 + $rowOffset; // Commence à la ligne 2 après le logo
        foreach ($evenements as $evenement) {
            $sheet->setCellValue('B' . $row, $evenement->getId_event());
            $sheet->setCellValue('C' . $row, $evenement->getType());
            $sheet->setCellValue('D' . $row, $evenement->getAdresseEvenement());
            $sheet->setCellValue('E' . $row, $evenement->getDateEvenement()->format('Y-m-d'));
            $sheet->setCellValue('F' . $row, $evenement->getPlacesDispo());
            // Ajoute d'autres colonnes si nécessaire
            $row++;
        }

        // Spécifie le nom du fichier Excel
        $fileName = 'evenements.xlsx';

        // Sauvegarde le fichier Excel dans le répertoire d'export
        $exportPath = $this->getParameter('kernel.project_dir') . '/public/exports/' . $fileName;
        $writer = new Xlsx($spreadsheet);
        $writer->save($exportPath);

        // Répond à la requête avec un message de succès
        return new Response('Fichier Excel généré avec succès : <a href="/exports/' . $fileName . '">Télécharger</a>');
    }
}
