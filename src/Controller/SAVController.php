<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Contrat;
use App\Entity\SAVSearch;
use App\Form\PDFType;
use App\Form\SAVSearchType;
use App\Entity\CommentairesSAV;
use App\Form\CommentairesSAVType;
use App\Entity\RepCommentairesSAV;
use App\Form\RepCommentairesSAVType;
use App\Repository\ContratRepository;
use App\Repository\PhotosSAVRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentairesSAVRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RepCommentairesSAVRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Controller\PdfFileController as ControllerPdfFileController;

#[Route('/sav')]
#[IsGranted('ROLE_GESTION')]
class SAVController extends AbstractController
{
    #[Route('/', name: 'app_sav')]
    public function index(Request $request): Response
    {
        $savSearch = new SAVSearch;
        $searchForm = $this->createForm(SAVSearchType::class, $savSearch);

        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            // error message
            throw new Exception('There\'s a problem with the JS');
        }
        return $this->render('sav/index.html.twig', [
            'current_page' => 'app_sav',
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_sav_contrat')]
    public function show(
        Contrat $contrat, 
        CommentairesSAVRepository $commentairesSAVRepository, 
        Request $request, EntityManagerInterface $em, 
        RepCommentairesSAVRepository $repCommentairesSAVRepository,
        PhotosSAVRepository $photosSAVRepository,
        ControllerPdfFileController $pdfFileController
        ): Response
    {
        $comments = $commentairesSAVRepository->findBy(
            ['codeContrat'  =>  $contrat->getId()],
            ['date_com'     =>  'DESC'],
        );
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
        $replies = $repCommentairesSAVRepository->findBy(
            ['codeClient' => $contrat->getCodeClient()->getId()],
            ['date_com'     =>  'DESC'],
            ) ?? null;

        // On crée le formulaires pour les réponses
        $reply = new RepCommentairesSAV();
        $replyForm = $this->createForm(RepCommentairesSAVType::class, $reply);
        $replyForm->handleRequest($request);
 
        if ($replyForm->isSubmitted() && $replyForm->isValid()) {
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

        /**
         * Partie Devis
         */
        //On crée une liste pour les devis
        $devis = [];
        
        //On définit le prefix du pdf pour chercher dans le fichier
        $prefix = "Devis_"; 
        
        //On définit le chemin du devis pour ce client. J'utilise la fonction "getParameter" qui prend le parametre pour le chemin
        //qui est une variable globale dans le fichier service.yaml
        $devisPath = $this->getParameter('devis_sav_chemin') . $contrat->getCodeClient()->getId() ?? null;


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
            $dossier = "Devis_SAV/";
            $codeContrat = $contrat->getId();
            $codeClient =  $contrat->getCodeClient()->getId();
            $pattern = "Devis_" . $codeContrat . "_" . $codeClient . "_";
            $verif = '/^' . preg_quote($pattern, '/') . '.*\.pdf$/i';
            try {

                $nomCorrect = $pdfFileController->uploadPdfFromForm($form->get('pdfFile'), $dossier, $codeClient, $verif);
                
                // Si le fichier n'a pas le bon nom, récupére un NULL de PdfFileController pour envoyer un message d'erreur
                if ($nomCorrect == NULL){
                    $errorMessage = "Le nom du fichier ne correspond pas à la page SAV actuel.";
                }

            } catch (FileException $e) {
                dd('az');
                $this->addFlash(
                    'errorUpload',
                    'Une erreur s`\'est produite lors du traitement du fichier'
                 );
            }
        }

        return $this->render('sav/show.html.twig', [
            'current_page'      => 'app_sav',
            'contrat'           => $contrat,
            'comments'          => $comments,
            'restCommentaires'  => null,
            'replies'           => $replies,
            'commentForm'       => $commentForm->createView(),
            'replyForm'         => $replyForm->createView(),
            'photos'            => $photos,
            'devis'             => $devis,
            'form'              => $form->createView(),
            'error'             => $errorMessage,
        ]);
    }
}
