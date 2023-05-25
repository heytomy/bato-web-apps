<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfFileController extends AbstractController

{
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
    
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '.' . $file->guessExtension();
        if (preg_match($pattern, $newFilename)) {        
            $targetUpload = $this->targetDirectory . $dossierDevis . $numDossier . '/';
            $targetPath = $targetUpload . $newFilename;
            
        
            // Créer le dossier cible si nécessaire
            $this->createDirectoryIfNotExists($targetUpload);
        
            // Déplacer le fichier vers le dossier cible en le renommant
            $file->move($targetUpload, $newFilename);
        
            // Mettre à jour le chemin du fichier avec le nouveau nom de fichier
            $filePath = $targetPath;
        
            return $filePath;
        }
    }

    private function createDirectoryIfNotExists($targetUpload)
    {
        if (!is_dir($targetUpload)) {
            mkdir($targetUpload, 0777, true);
        }
    }

   
}

