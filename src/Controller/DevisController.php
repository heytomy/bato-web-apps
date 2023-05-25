<?php

namespace App\Controller;

use DateTime;
use App\Form\PDFType;
use App\Entity\DevisARealiser;
use App\Repository\PhotosDevisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\PdfFileController as ControllerPdfFileController;

#[Route('/devis')]
class DevisController extends AbstractController
{
    #[Route('/', name: 'app_devis')]
    public function index(): Response
    {
        return $this->render('devis/index.html.twig', [
            'current_page' => 'app_devis',
        ]);
    }

    #[Route('/{id}', name:'app_devis_show')]
    public function show(
        DevisARealiser $devis,
        Request $request, 
        PhotosDevisRepository $photosDevisRepository,
        ControllerPdfFileController $pdfFileController
    )
    {
        /**
         * partie photos
         */
        $photos = $photosDevisRepository->findBy(['codeDevis' => $devis->getId()]) ?? null;
        /**
         * Partie Devis
         */
        //On crée une liste pour les devis
        $devisCreer = [];
        
        //On définit le prefix du pdf pour chercher dans le fichier
        $prefix = "Devis_"; 
        
        //On définit le chemin du devis pour ce client. J'utilise la fonction "getParameter" qui prend le parametre pour le chemin
        //qui est une variable globale dans le fichier service.yaml
        $devisPath = $this->getParameter('devis_creer_chemin') . $devis->getNom() . "_" ?? null;                                                                        // FINIR ICI
        


        /**
         * Ici, on scan le dossier pour trouver tous les fichier. On filtre les fichier qu'on veut et les mettre dans la liste "devis"
         * On va utiliser ces noms des fichiers dans le twig pour appeler le controller devis avec la Route 'app_devis'
         */
        try {
            $files = scandir($devisPath);
            foreach ($files as $file) {
                if (strpos($file, $prefix) !== false) {
                    $devisCreer[] = $file;
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

         $form = $this->createForm(PDFType::class);
         $form->handleRequest($request);
 
         // Enregistrement du fichier
         if ($form->isSubmitted() && $form->isValid()) {
            $dossier = "Devis_CREER/";           
            $nomClient = $devis->getNom();
            $currentDate = new \DateTime();
            $monthYear = $currentDate->format('dmY');
            $nomPdf = $nomClient . "_" . $monthYear;
            $pattern = "Devis_";
            $verif = '/^' . preg_quote($pattern, '/') . '.*' . preg_quote($monthYear, '/') . '\.pdf$/i';

            try {
 
                $pdfFileController->uploadPdfFromForm($form->get('pdfFile'), $dossier, $nomPdf, $verif);
 
            } catch (BadRequestHttpException $e) {
 
                echo 'Erreur : '.$e->getMessage();
            }
        }
   

        return $this->render('devis/show.html.twig', [
            'current_page'  =>  'app_devis',
            'devis'         =>  $devis,
            'photos'        =>  $photos,
            'form'          => $form->createView(),
        ]);

    }

    #[Route('/sav/{id}/{filename}', name: 'app_devis_sav', methods: ['POST', 'GET'])]
    public function devisSAV($id, $filename)
    {
        $devispath = $this->getParameter('devis_sav_chemin').$id;

        $filePath = $devispath .'/'.$filename;
        return new BinaryFileResponse($filePath);
    }

    #[Route('/chantier/{id}/{filename}', name: 'app_devis_chantier', methods: ['POST', 'GET'])]
    public function devisChantier($id, $filename)
    {
        $devispath = $this->getParameter('devis_chantier_chemin').$id;

        $filePath = $devispath .'/'.$filename;
        return new BinaryFileResponse($filePath);
    }
}
