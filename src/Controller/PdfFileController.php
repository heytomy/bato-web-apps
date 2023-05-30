<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfFileController extends AbstractController

{
    // Récupération du chemin vers le dossier Devis
    private $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }


    public function uploadPdfFromForm($formField, $dossierDevis, $numDossier, $pattern)
    {
        /** @var UploadedFile $file */
        $file = $formField->getData();
    
        // Vérifier si un fichier a été soumis
        if (!$file instanceof UploadedFile) {
            return null;
        }
    
        // Vérifier que le PDF porte bien le nom attendu pour son enregistrement
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '.' . $file->guessExtension();
        if (preg_match($pattern, $newFilename)) {        
            $targetUpload = $this->targetDirectory . $dossierDevis . $numDossier . '/';
            $targetPath = $targetUpload . $newFilename;
            
        
            // Créer le dossier cible si nécessaire
            $this->createDirectoryIfNotExists($targetUpload);
        
            // Déplacer le fichier vers le dossier cible
            $file->move($targetUpload, $newFilename);
        
            // Mettre à jour le chemin du fichier avec le nouveau nom de fichier
            $filePath = $targetPath;
        
            return $filePath;
        }

        else {
            return NULL;
        }
    }

    // Création d'un dossier si non existant
    private function createDirectoryIfNotExists($targetUpload)
    {
        if (!is_dir($targetUpload)) {
            mkdir($targetUpload, 0777, true);
        }
    }

   
}

