<?php

namespace App\Controller;

use App\Entity\CommentairesSAV;
use App\Entity\RepCommentairesSAV;
use App\Repository\CommentairesSAVRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RepCommentairesSAVRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sav/comment')]
class CommentaireSAVController extends AbstractController
{
    #[Route('/{id}', name: 'app_sav_commentaire_delete', methods: ['POST'])]
    public function deleteComment(
        Request $request,
        CommentairesSAV $comment,
        CommentairesSAVRepository $commentairesSAVRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentairesSAVRepository->remove($comment, true);
        }

        return $this->redirectToRoute('app_sav_contrat', ['id' => $comment->getCodeContrat()->getId()]);
    }

    #[Route('/reply/{id}', name: 'app_sav_reply_delete', methods: ['POST'])]
    public function deleteReply(
        Request $request,
        RepCommentairesSAV $reply,
        RepCommentairesSAVRepository $repCommentairesSAVRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reply->getId(), $request->request->get('_token'))) {
            $repCommentairesSAVRepository->remove($reply, true);
        }

        return $this->redirectToRoute('app_sav_contrat', ['id' => $reply->getParent()->getCodeContrat()->getId()]);
    }
}
