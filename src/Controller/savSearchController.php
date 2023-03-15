<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class savSearchController extends AbstractController
{
    #[Route('/sav/search', name: 'app_sav_search')]
    public function index(): Response
    {
        return $this->render('sav_search/index.html.twig', [
            'controller_name' => 'ClientSAVSearchController',
        ]);
    }
}
