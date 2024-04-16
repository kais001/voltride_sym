<?php

namespace App\Controller;

use App\Entity\ServiceApreslocation;
use App\Form\ServiceApreslocationType;
use App\Repository\ServiceApreslocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/service/apreslocation')]
class ServiceApreslocationController extends AbstractController
{
    #[Route('/c', name: 'app_service_apreslocation_index', methods: ['GET'])]
    public function index(ServiceApreslocationRepository $serviceApreslocationRepository): Response
    {
        return $this->render('service_apreslocation/index.html.twig', [
            'service_apreslocations' => $serviceApreslocationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_apreslocation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serviceApreslocation = new ServiceApreslocation();
        $form = $this->createForm(ServiceApreslocationType::class, $serviceApreslocation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serviceApreslocation);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_apreslocation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_apreslocation/new.html.twig', [
            'service_apreslocation' => $serviceApreslocation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idservice}', name: 'app_service_apreslocation_show', methods: ['GET'])]
    public function show(ServiceApreslocation $serviceApreslocation): Response
    {
        return $this->render('service_apreslocation/show.html.twig', [
            'service_apreslocation' => $serviceApreslocation,
        ]);
    }

    #[Route('/{idservice}/edit', name: 'app_service_apreslocation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ServiceApreslocation $serviceApreslocation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceApreslocationType::class, $serviceApreslocation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_service_apreslocation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_apreslocation/edit.html.twig', [
            'service_apreslocation' => $serviceApreslocation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idservice}', name: 'app_service_apreslocation_delete', methods: ['POST'])]
    public function delete(Request $request, int $idservice, EntityManagerInterface $entityManager): Response
    {
        $serviceApreslocation = $entityManager->getRepository(ServiceApreslocation::class)->find($idservice);

        if (!$serviceApreslocation) {
            throw $this->createNotFoundException('ServiceApreslocation not found');
        }

        if ($this->isCsrfTokenValid('delete'.$serviceApreslocation->getIdservice(), $request->request->get('_token'))) {
            $entityManager->remove($serviceApreslocation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_apreslocation_index', [], Response::HTTP_SEE_OTHER);
    }
}