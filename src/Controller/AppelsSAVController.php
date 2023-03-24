<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppelsSAVController extends AbstractController
{
    #[Route('/appels/s/a/v', name: 'app_appels_s_a_v')]
    public function index(): Response
    {
        return $this->render('appels_sav/index.html.twig', [
            'controller_name' => 'AppelsSAVController',
        ]);
    }
}
