<?php

namespace App\Controller\Admin;

use App\Entity\AppsUtilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\Response;

class AppsUtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AppsUtilisateur::class;
    }

    public static function test(): Response
    {
        dd('hi');
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
