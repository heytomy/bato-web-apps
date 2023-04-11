<?php

namespace App\Controller;

use App\Entity\PhotosAppels;
use App\Repository\PhotosAppelsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/appels/photo')]
class PhotosAppelsController extends AbstractController
{
    #[Route('/{id}', name: 'app_appels_photo_delete', methods: ['POST'])]
    public function deleteComment(
        Request $request,
        PhotosAppels $photo,
        PhotosAppelsRepository $photosAppelsRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $photosAppelsRepository->remove($photo, true);
        }

        return $this->redirectToRoute('app_appels_show', ['id' => $photo->getIdAppel()->getId()]);
    }
}
