<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TermsController extends AbstractController
{
    #[Route('/cgu', name: 'app_terms_cgu')]
    public function cgu(): Response
    {
        return $this->render('terms/cgu.html.twig', [
        ]);
    }

    #[Route('/rgpd', name: 'app_terms_rgpd')]
    public function rgpd(): Response
    {
        return $this->render('terms/rgpd.html.twig', [
        ]);
    }
}
