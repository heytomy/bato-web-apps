<?php

namespace App\Controller;

use App\Repository\AppelsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppelsController extends AbstractController
{
    #[Route('/appels', name: 'app_appels')]
    public function index(AppelsRepository $appelsRepository): Response
    {
        $appels = $appelsRepository->findAll();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('appels/index.html.twig', [
            'appels' => $appels,
        ]);
    }
}
