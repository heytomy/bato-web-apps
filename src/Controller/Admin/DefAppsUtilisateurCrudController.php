<?php

namespace App\Controller\Admin;

use App\Entity\DefAppsUtilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DefAppsUtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DefAppsUtilisateur::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('Nom'),
            TextField::new('Prenom'),
            TextareaField::new('Adresse'),
            TextField::new('CP'),
            TextField::new('Ville'),
            TelephoneField::new('Tel_1'),
            TelephoneField::new('Tel_2'),
            EmailField::new('Mail'),
        ];
    }

}
