<?php

namespace App\Controller;

use App\Entity\CommentairesAppels;
use App\Entity\RepCommentairesAppels;
use App\Repository\CommentairesAppelsRepository;
use App\Repository\RepCommentairesAppelsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/appels/comment')]
class CommentaireAppelsController extends AbstractController
{
    #[Route('/{id}', name: 'app_appels_commentaire_delete', methods: ['POST'])]
    public function deleteComment(
        Request $request,
        CommentairesAppels $comment,
        CommentairesAppelsRepository $commentairesAppelsRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentairesAppelsRepository->remove($comment, true);
        }

        return $this->redirectToRoute('app_appels_show', ['id' => $comment->getCodeAppels()]);
    }

    #[Route('/reply/{id}', name: 'app_appels_reply_delete', methods: ['POST'])]
    public function deleteReply(
        Request $request,
        RepCommentairesAppels $reply,
        RepCommentairesAppelsRepository $repCommentairesAppelsRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reply->getId(), $request->request->get('_token'))) {
            $repCommentairesAppelsRepository->remove($reply, true);
        }

        return $this->redirectToRoute('app_sav_contrat', ['id' => $reply->getParent()->getCodeAppels()]);
    }
}
