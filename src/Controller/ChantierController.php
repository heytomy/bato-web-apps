<?php

namespace App\Controller;

use App\Entity\Calendrier;
use DateTime;
use App\Form\PDFType;
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
use App\Controller\PdfFileController as ControllerPdfFileController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

            $dateDebut = $form->get('dateDebut')->getData()->format('Y-m-d');;
            $dateFin = $form->get('dateFin')->getData()->format('Y-m-d');;

            $chantier->setStatut($statutEnCours);

            if ($dateFin !== null && new DateTime($dateDebut) > new DateTime($dateFin)) {
                $this->addFlash(
                    'error',
                    'La date et heure de fin doit être postérieure à la date et heure de début.'
                );
            } else {
                $rdv
                    ->setTitre($chantier->getCodeClient()->getNom())
                    ->setDateDebut($chantier->getDateDebut())
                    ->setDateFin($chantier->getDateFin())
                    ->setAllDay(false)
                    ->setChantier($chantier);

                $chantier
                    ->setDescription(strip_tags($form->get('description')->getData()));


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
        }
        return $this->render('chantier/new.html.twig', [
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
        ControllerPdfFileController $pdfFileController
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

        /**
         * Partie Devis
         */
        //On crée une liste pour les devis
        $devis = [];
        
        //On définit le prefix du pdf pour chercher dans le fichier
        $prefix = "Devis_"; 
        
        //On définit le chemin du devis pour ce client. J'utilise la fonction "getParameter" qui prend le parametre pour le chemin
        //qui est une variable globale dans le fichier service.yaml
        $devisPath = $this->getParameter('devis_chantier_chemin') . $chantier->getId() ?? null;
        // dd($devisPath);


        /**
         * Ici, on scan le dossier pour trouver tous les fichier. On filtre les fichier qu'on veut et les mettre dans la liste "devis"
         * On va utiliser ces noms des fichiers dans le twig pour appeler le controller devis avec la Route 'app_devis'
         */
        try {
            $files = scandir($devisPath);
            foreach ($files as $file) {
                if (strpos($file, $prefix) !== false) {
                    $devis[] = $file;
                }
            }
        } catch (\Throwable $th) {
            $this->addFlash(
               'noDevis',
               'Pas de devis'
            );
        }

        /**
         * Partie enregistrement de devis
         */

         // On récupére le PDF depuis le formulaire
         $form = $this->createForm(PDFType::class);
         $form->handleRequest($request);
         $errorMessage = "";
 
         // On crée le nom du dossier au besoin et on génére la variable de vérification du nom de fichier
         if ($form->isSubmitted() && $form->isValid()) {
            $dossier = "Devis_CHANTIERS/";                 
            $codeChantier = $chantier->getId();
            $codeClient =  $chantier->getCodeClient()->getId();
            $pattern = "Devis_" . $codeChantier . "_" . $codeClient . "_";
            $verif = '/^' . preg_quote($pattern, '/') . '.*\.pdf$/i';

            try { 

                $nomCorrect = $pdfFileController->uploadPdfFromForm($form->get('pdfFile'), $dossier, $codeChantier, $verif);     
                
                // Si le fichier n'a pas le bon nom, récupére un NULL de PdfFileController pour envoyer un message d'erreur
                if ($nomCorrect == NULL){
                    $errorMessage = "Le nom du fichier ne correspond pas à la page chantier actuel.";
                }

            } catch (FileException $e) {
                dd('az');
                $this->addFlash(
                    'errorUpload',
                    'Une erreur s`\'est produite lors du traitement du fichier'
                 );
            }
        }

        return $this->render('chantier/show.html.twig',[
            'chantier'          =>  $chantier,
            'comments'          =>  $comments,
            'restCommentaires'  =>  null,
            'commentForm'       =>  $commentForm,
            'replyForm'         =>  $replyForm,
            'replies'           =>  $replies,
            'photos'            =>  $photos,
            'current_page'      =>  'app_chantier',
            'devis'             =>  $devis,
            'form'              =>  $form->createView(),
            'error'             =>  $errorMessage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chantier_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        ChantierApps $chantier,
        EntityManagerInterface $em,
        StatutChantierRepository $statutChantierRepository,
    ): Response {
        $statutEnCours = $statutChantierRepository->findOneBy(['statut' => 'EN_COURS']);
        if ($chantier->getStatut() !== $statutEnCours) {
            throw new Exception('Le statut du chantier n\'est pas le bon', 406);
        }

        $form = $this->createForm(ChantierAppsType::class, $chantier);
        $form->handleRequest($request);
        $rdv = $chantier->getRdv();

        if ($form->isSubmitted() && $form->isValid()) {
            
            $dateDebut = $form->get('dateDebut')->getData()->format('Y-m-d');;
            $dateFin = $form->get('dateFin')->getData()->format('Y-m-d');;

            if ($dateFin !== null && new DateTime($dateDebut) > new DateTime($dateFin)) {
                $this->addFlash(
                    'error',
                    'La date et heure de fin doit être postérieure à la date et heure de début.'
                );
            } else {
                $rdv
                    ->setTitre($chantier->getCodeClient()->getNom())
                    ->setDateDebut($chantier->getDateDebut())
                    ->setDateFin($chantier->getDateFin())
                    ->setAllDay(false)
                    ->setChantier($chantier);

                $em->persist($chantier);
                $em->flush($chantier);

                $em->persist($rdv);
                $em->flush($rdv);
                return $this->redirectToRoute('app_chantier_show', ['id' => $chantier->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('chantier/edit.html.twig', [
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

        return $this->render('chantier/edit.html.twig', [
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
