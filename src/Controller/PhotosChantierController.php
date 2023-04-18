<?php

namespace App\Controller;

use App\Entity\PhotosChantier;
use App\Repository\PhotosChantierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/chantier/photo')]
class PhotosChantierController extends AbstractController
{
    #[Route('/{id}', name: 'app_chantier_photo_delete', methods: ['POST'])]
    public function deleteComment(
        Request $request,
        PhotosChantier $photo,
        PhotosChantierRepository $photosChantierRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $photosChantierRepository->remove($photo, true);
        }

        return $this->redirectToRoute('app_chantier_show', ['id' => $photo->getCodeChantier()->getId()]);
    }
}
