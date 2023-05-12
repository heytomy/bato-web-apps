<?php

namespace App\Controller;

use App\Entity\PhotosDevis;
use App\Repository\PhotosDevisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/devis/photo')]
class PhotosDevisController extends AbstractController
{
    #[Route('/{id}', name: 'app_devis_photo_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        PhotosDevis $photo,
        PhotosDevisRepository $photosDevisRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $photosDevisRepository->remove($photo, true);
        }

        return $this->redirectToRoute('app_devis_show', ['id' => $photo->getCodeDevis()->getId()]);
    }
}
