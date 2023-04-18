<?php

namespace App\Controller;

use App\Entity\CommentairesChantier;
use App\Entity\RepCommentairesChantier;
use App\Repository\CommentairesChantierRepository;
use App\Repository\RepCommentairesChantierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/chantier/comment')]
class CommentaireChantierController extends AbstractController
{
    #[Route('/{id}', name: 'app_chantier_commentaire_delete', methods: ['POST'])]
    public function deleteComment(
        Request $request,
        CommentairesChantier $comment,
        CommentairesChantierRepository $commentairesChantierRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentairesChantierRepository->remove($comment, true);
        }

        return $this->redirectToRoute('app_chantier_show', ['id' => $comment->getCodeChantier()->getId()]);
    }

    #[Route('/reply/{id}', name: 'app_chantier_reply_delete', methods: ['POST'])]
    public function deleteReply(
        Request $request,
        RepCommentairesChantier $reply,
        RepCommentairesChantierRepository $repCommentairesChantierRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reply->getId(), $request->request->get('_token'))) {
            $repCommentairesChantierRepository->remove($reply, true);
        }

        return $this->redirectToRoute('app_chantier_show', ['id' => $reply->getCodeChantier()->getId()]);
    }
}
