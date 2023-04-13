<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/chantier')]
#[IsGranted('ROLE_GESTION')]
class ChantierController extends AbstractController
{
    #[Route('/', name: 'app_chantier')]
    public function index(): Response
    {
        return $this->render('chantier/index.html.twig', [
            'current_page' => 'app_chantier',
        ]);
    }
}
