<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DevisController extends AbstractController
{
    // #[Route('/devis', name: 'app_devis')]
    // public function index(): Response
    // {
    //     return $this->render('devis/index.html.twig', [
    //         'controller_name' => 'DevisController',
    //     ]);
    // }

    #[Route('/devis/sav/{id}/{filename}', name: 'app_devis_sav', methods: ['POST', 'GET'])]
    public function devisSAV($id, $filename)
    {
        $devispath = $this->getParameter('devis_sav_chemin').$id;

        $filePath = $devispath .'/'.$filename;
        return new BinaryFileResponse($filePath);
    }

    #[Route('/devis/chantier/{id}/{filename}', name: 'app_devis_chantier', methods: ['POST', 'GET'])]
    public function devisChantier($id, $filename)
    {
        $devispath = $this->getParameter('devis_chantier_chemin').$id;

        $filePath = $devispath .'/'.$filename;
        return new BinaryFileResponse($filePath);
    }
}
