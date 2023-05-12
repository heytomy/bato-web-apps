<?php

namespace App\Controller;

use App\Entity\DevisARealiser;
use App\Repository\PhotosDevisRepository;
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

    #[Route('/{id}', name:'app_devis_show')]
    public function show(
        DevisARealiser $devis,
        PhotosDevisRepository $photosDevisRepository
    )
    {
        /**
         * partie photos
         */
        $photos = $photosDevisRepository->findBy(['codeDevis' => $devis->getId()]) ?? null;

        return $this->render('devis/show.html.twig', [
            'current_page'  =>  'app_devis',
            'devis'         =>  $devis,
            'photos'        =>  $photos,
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
