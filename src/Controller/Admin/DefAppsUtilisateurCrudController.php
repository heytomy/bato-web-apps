<?php

namespace App\Controller\Admin;

use App\Entity\DefAppsUtilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DefAppsUtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DefAppsUtilisateur::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
