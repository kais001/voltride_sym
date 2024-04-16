<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/fo', name: 'app_fo')]
    public function index(): Response
    {
        dump('Controller executed!'); // This will be displayed in the Symfony Profiler toolbar or log files.
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
}
