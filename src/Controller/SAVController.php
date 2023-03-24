<?php

namespace App\Controller;

use App\Entity\CommentairesSAV;
use App\Entity\Contrat;
use App\Entity\RepCommentairesSAV;
use App\Form\CommentairesSAVType;
use App\Form\RepCommentairesSAVType;
use App\Repository\CommentairesSAVRepository;
use App\Repository\ContratRepository;
use App\Repository\PhotosSAVRepository;
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

    #[Route('/{id}', name: 'app_sav_contrat')]
    public function show(
        Contrat $contrat, 
        CommentairesSAVRepository $commentairesSAVRepository, 
        Request $request, EntityManagerInterface $em, 
        RepCommentairesSAVRepository $repCommentairesSAVRepository,
        PhotosSAVRepository $photosSAVRepository
        ): Response
    {
        $comments = $commentairesSAVRepository->findBy(['codeContrat' => $contrat->getId()]);
        $user = $this->getUser() ?? null;
        $nom = $user->getIdUtilisateur()->getNom() ." ". $user->getIdUtilisateur()->getPrenom();
        
        /**
         * Partie commentaires
         */
        // On crée le formulaires pour les commentaires
        $comment = new CommentairesSAV();
        $commentForm = $this->createForm(CommentairesSAVType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment
                ->setDateCom(new DateTime())
                ->setCodeContrat($contrat)
                ->setCodeClient($contrat->getCodeClient()->getId())
                ->setNom($nom)
                ->setOwner($user->getIDUtilisateur())
                ;
            
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_sav_contrat', ['id' => $contrat->getId()]);
        }

        /**
         * Partie Réponses
         */
        // On récupère toutes les réponses liées à ce contrat
        $replies = $repCommentairesSAVRepository->findBy(['codeClient' => $contrat->getCodeClient()->getId()]) ?? null;

        // On crée le formulaires pour les réponses
        $reply = new RepCommentairesSAV();
        $replyForm = $this->createForm(RepCommentairesSAVType::class, $reply);
        $replyForm->handleRequest($request);
 
        if ($replyForm->isSubmitted() && $replyForm->isValid()) {
            dump('we reached form submit');
            $reply
                ->setDateCom(new DateTime())
                ->setCodeClient($contrat->getCodeClient()->getId())
                ->setNom($nom)
                ->setOwner($user->getIDUtilisateur())
                ;

            // On récupère le contenu du champ parentid
            $parentid = $replyForm->get('parentid')->getData();

            // On va chercher le commentaire correspondant
            $parent = $commentairesSAVRepository->find($parentid);

            // On définit le parent
            $reply->setParent($parent);
            
            $em->persist($reply);
            $em->flush();
            return $this->redirectToRoute('app_sav_contrat', ['id' => $contrat->getId()]);
        }

        /**
         * Partie Photos
         */
        $photos = $photosSAVRepository->findBy(['Code' => $contrat->getId()]) ?? null;

        return $this->render('sav/show.html.twig', [
            'current_page'      => 'app_sav',
            'contrat'           => $contrat,
            'comments'          => $comments,
            'restCommentaires'  => null,
            'replies'           => $replies,
            'commentForm'       => $commentForm->createView(),
            'replyForm'         => $replyForm->createView(),
            'photos'            => $photos,
        ]);
    }
}
