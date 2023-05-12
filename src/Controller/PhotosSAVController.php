<?php

namespace App\Controller;

use App\Entity\PhotosSAV;
use App\Repository\PhotosSAVRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sav/photo')]
class PhotosSAVController extends AbstractController
{
    #[Route('/{id}', name: 'app_sav_photo_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        PhotosSAV $photo,
        PhotosSAVRepository $photosSAVRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $photosSAVRepository->remove($photo, true);
        }

        return $this->redirectToRoute('app_sav_contrat', ['id' => $photo->getCode()->getId()]);
    }
}
