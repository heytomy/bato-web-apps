<?php

namespace App\Controller;

use App\Repository\EmergencyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppelsController extends AbstractController
{
    #[Route('/appels', name: 'app_appels')]
    public function list(EmergencyRepository $emergencyRepository): Response
    {
        $emergencies = $emergencyRepository->findBy([], ['priority' => 'DESC']);

        return $this->render('appels/index.html.twig', [
            'emergencies' => $emergencies,
        ]);
    }
}
