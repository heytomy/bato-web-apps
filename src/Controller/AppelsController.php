<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppelsController extends AbstractController
{
    #[Route('/appels', name: 'app_appels')]
    public function index(): Response
    {
        return $this->render('appels/index.html.twig', [
            'controller_name' => 'AppelsController',
        ]);
    }
}
