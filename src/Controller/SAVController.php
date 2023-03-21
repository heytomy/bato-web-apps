<?php

namespace App\Controller;

use App\Entity\CommentairesSAV;
use App\Entity\Contrat;
use App\Entity\RepCommentairesSAV;
use App\Form\CommentairesSAVType;
use App\Form\RepCommentairesSAVType;
use App\Repository\CommentairesSAVRepository;
use App\Repository\ContratRepository;
use App\Repository\RepCommentairesSAVRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/sav')]
#[IsGranted('ROLE_GESTION')]
class SAVController extends AbstractController
{
    #[Route('/', name: 'app_sav')]
    public function index(ContratRepository $contratRepository): Response
    {          
        return $this->render('sav/index.html.twig', [
            'current_page' => 'app_sav',
        ]);
    }

    #[Route('/comment/{id}/reply', name: 'app_sav_reply')]
    public function reply(CommentairesSAV $parent, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser() ?? null;
        $nom = $user->getIdUtilisateur()->getNom() ." ". $user->getIdUtilisateur()->getPrenom();

        $reply = new RepCommentairesSAV();
        $replyForm = $this->createForm(RepCommentairesSAVType::class, $reply);
        $replyForm->handleRequest($request);
        if ($replyForm->isSubmitted() && $replyForm->isValid()) {
            $reply
                ->setDateCom(new DateTime())
                ->setParent($parent)
                ->setCodeClient($user->getId())
                ->setNom($nom)
                ;
            
            $em->persist($reply);
            $em->flush();
            return $this->redirectToRoute('app_sav_contrat', ['id' => $parent->getCodeContrat()->getId()]);
        }

        return $this->render('_inc/_reply-form.html.twig', [
            'form' => $replyForm->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_sav_contrat')]
    public function show(Contrat $contrat, CommentairesSAVRepository $commentairesSAVRepository, Request $request, EntityManagerInterface $em, RepCommentairesSAVRepository $repCommentairesSAVRepository): Response
    {
        $comments = $commentairesSAVRepository->findBy(['codeContrat' => $contrat->getId()]);
        $user = $this->getUser() ?? null;
        $replies = $repCommentairesSAVRepository->findAll() ?? null;
        $nom = $user->getIdUtilisateur()->getNom() ." ". $user->getIdUtilisateur()->getPrenom();
        
        /**
         * Partie commentaires
         */
        $comment = new CommentairesSAV();
        $commentForm = $this->createForm(CommentairesSAVType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment
                ->setDateCom(new DateTime())
                ->setCodeContrat($contrat)
                ->setCodeClient($user->getId())
                ->setNom($nom)
                ;
            
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_sav_contrat', ['id' => $contrat->getId()]);
        }

        return $this->render('sav/show.html.twig', [
            'current_page' => 'app_sav',
            'contrat' => $contrat,
            'comments' => $comments,
            'restCommentaires' => null,
            'replies' => $replies,
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
