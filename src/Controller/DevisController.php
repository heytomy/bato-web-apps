<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/devis')]
class DevisController extends AbstractController
{
    #[Route('/', name: 'app_devis')]
    public function index(): Response
    {
        return $this->render('devis/index.html.twig', [
            'current_page' => 'app_devis',
        ]);
    }

    #[Route('/sav/{id}/{filename}', name: 'app_devis_sav', methods: ['POST', 'GET'])]
    public function devisSAV($id, $filename)
    {
        $devispath = $this->getParameter('devis_sav_chemin').$id;

        $filePath = $devispath .'/'.$filename;
        return new BinaryFileResponse($filePath);
    }

    #[Route('/chantier/{id}/{filename}', name: 'app_devis_chantier', methods: ['POST', 'GET'])]
    public function devisChantier($id, $filename)
    {
        $devispath = $this->getParameter('devis_chantier_chemin').$id;

        $filePath = $devispath .'/'.$filename;
        return new BinaryFileResponse($filePath);
    }
}
