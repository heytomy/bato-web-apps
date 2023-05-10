<?php

namespace App\Controller;

use App\Entity\Calendrier;
use DateTime;
use App\Entity\ChantierApps;
use App\Form\ChantierAppsType;
use App\Entity\CommentairesChantier;
use App\Entity\RepCommentairesChantier;
use App\Form\ChantierTermineType;
use App\Form\CommentairesChantierType;
use App\Form\RepCommentairesChantierType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChantierAppsRepository;
use App\Repository\PhotosChantierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentairesChantierRepository;
use App\Repository\RepCommentairesChantierRepository;
use App\Repository\StatutChantierRepository;
use Exception;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/chantier')]
#[IsGranted('ROLE_GESTION')]
class ChantierController extends AbstractController
{
    #[Route('/', name: 'app_chantier')]
    public function index(): Response
    {
        return $this->render('chantier/index.html.twig', [
            'current_page' => 'app_chantier',
        ]);
    }

    #[Route('/new', name: 'app_chantier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, StatutChantierRepository $statutChantierRepository): Response
    {
        $statutEnCours = $statutChantierRepository->findOneBy(['statut' => 'EN_COURS']);
        
        $chantier = new ChantierApps();
        
        $rdv = new Calendrier();
        
        $form = $this->createForm(ChantierAppsType::class, $chantier);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chantier->setStatut($statutEnCours);
            $cleanDescription = strip_tags($form->get('description')->getData());

            $rdv
                ->setTitre($chantier->getCodeClient()->getNom())
                ->setDateDebut($chantier->getDateDebut())
                ->setDateFin($chantier->getDateFin())
                ->setAllDay(false)
                ->setChantier($chantier)
                ;

            $chantier
                ->setDescription($cleanDescription);
            

            $em->persist($chantier);
            $em->flush($chantier);

            $em->persist($rdv);
            $em->flush($rdv);

            $this->addFlash(
                'success',
                'Chantier enregistré avec succès !'
            );
            // $chantierAppsRepository->save($chantier, true);

            return $this->redirectToRoute('app_chantier', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chantier/new.html.twig', [
            'chantier'      =>  $chantier,
            'form'          =>  $form,
            'current_page'  =>  'app_chantier',
        ]);
    }

    #[Route('/{id}', name: 'app_chantier_show', methods: ['GET', 'POST'])]
    public function show(
        ChantierApps $chantier, 
        CommentairesChantierRepository $commentairesChantierRepository, 
        Request $request, 
        EntityManagerInterface $em, 
        RepCommentairesChantierRepository $repCommentairesChantierRepository,
        PhotosChantierRepository $photosChantierRepository,
        ): Response
    {
        $user = $this->getUser() ?? null;
        $comments = $commentairesChantierRepository->findBy(['codeChantier' => $chantier->getId()]);
        $nom = $user->getIdUtilisateur()->getNom() ." ". $user->getIdUtilisateur()->getPrenom();

        /**
         * Partie commentaires
         */
        // On crée le formulaires pour les commentaires
        $comment = new CommentairesChantier();
        $commentForm = $this->createForm(CommentairesChantierType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment
                ->setDateCom(new DateTime())
                ->setNom($nom)
                ->setCodeChantier($chantier)
                ->setOwner($user->getIDUtilisateur())
                ;
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_chantier_show', ['id' => $chantier->getId()]);
        }

        /**
         * Partie Réponses
         */
        // On récupère toutes les réponses liées à ce contrat
        $replies = $repCommentairesChantierRepository->findByChantier($chantier->getId()) ?? null;

        // On crée le formulaires pour les réponses
        $reply = new RepCommentairesChantier();
        $replyForm = $this->createForm(RepCommentairesChantierType::class, $reply);
        $replyForm->handleRequest($request);
 
        if ($replyForm->isSubmitted() && $replyForm->isValid()) {
            $reply
                ->setDateCom(new DateTime())
                ->setNom($nom)
                ->setOwner($user->getIDUtilisateur())
                ->setCodeChantier($chantier)
                ;

            // On récupère le contenu du champ parentid
            $parentid = $replyForm->get('parentid')->getData();

            // On va chercher le commentaire correspondant
            $parent = $commentairesChantierRepository->find($parentid);

            // On définit le parent
            $reply->setParent($parent);
            
            $em->persist($reply);
            $em->flush();
            return $this->redirectToRoute('app_chantier_show', ['id' => $chantier->getId()]);
        }

        /**
         * partie photos
         */
        $photos = $photosChantierRepository->findBy(['codeChantier' => $chantier->getId()]) ?? null;

        return $this->render('chantier/show.html.twig',[
            'chantier'          =>  $chantier,
            'comments'          =>  $comments,
            'restCommentaires'  =>  null,
            'commentForm'       =>  $commentForm,
            'replyForm'         =>  $replyForm,
            'replies'           =>  $replies,
            'photos'            =>  $photos,
            'current_page'      =>  'app_chantier',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chantier_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        ChantierApps $chantier, 
        EntityManagerInterface $em,
        StatutChantierRepository $statutChantierRepository,
        ): Response
    {
        $statutEnCours = $statutChantierRepository->findOneBy(['statut' => 'EN_COURS']);
        if ($chantier->getStatut() !== $statutEnCours) {
            throw new Exception('Le statut du chantier n\'est pas le bon', 406);
        }

        $form = $this->createForm(ChantierAppsType::class, $chantier);
        $form->handleRequest($request);
        $rdv = $chantier->getRdv();

        if ($form->isSubmitted() && $form->isValid()) {
            $rdv
                ->setTitre($chantier->getCodeClient()->getNom())
                ->setDateDebut($chantier->getDateDebut())
                ->setDateFin($chantier->getDateFin())
                ->setAllDay(false)
                ->setChantier($chantier)
                ;

            $em->persist($chantier);
            $em->flush($chantier);

            $em->persist($rdv);
            $em->flush($rdv);
            return $this->redirectToRoute('app_chantier_show', ['id' => $chantier->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chantier/edit.html.twig', [
            'chantier'      =>  $chantier,
            'form'          =>  $form,
            'current_page'  =>  'app_chantier',
        ]);
    }

    #[Route('/{id}/edit/termine', name: 'app_chantier_edit_termine', methods: ['GET', 'POST'])]
    public function editTermine(
        Request $request, 
        ChantierApps $chantier, 
        EntityManagerInterface $em,
        StatutChantierRepository $statutChantierRepository,
        ): Response
    {
        $statutTermine = $statutChantierRepository->findOneBy(['statut' => 'TERMINE']);
        if ($chantier->getStatut() !== $statutTermine) {
            throw new Exception('Le statut du chantier n\'est pas le bon', 406);
        }

        $form = $this->createForm(ChantierTermineType::class, $chantier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($chantier);
            $em->flush($chantier);
            return $this->redirectToRoute('app_chantier_show', ['id' => $chantier->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chantier/edit.html.twig', [
            'chantier'      =>  $chantier,
            'form'          =>  $form,
            'current_page'  =>  'app_chantier',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_chantier_delete', methods: ['POST'])]
    public function delete(Request $request, ChantierApps $chantier, ChantierAppsRepository $chantierAppsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chantier->getId(), $request->request->get('_token'))) {
            $chantierAppsRepository->remove($chantier, true);
        } else {
            dd($request);
        }
        
        return $this->redirectToRoute('app_chantier', [], Response::HTTP_SEE_OTHER);
    }
}
