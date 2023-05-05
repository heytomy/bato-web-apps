<?php

namespace App\Controller\Admin;

use App\Entity\Appels;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

class AppelsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Appels::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnDetail()->hideOnIndex(),
            TextField::new('Nom', 'Nom'),
            TextField::new('Adr', 'Adresse'),
            TextField::new('CP', 'Code Postal'),
            TextField::new('Ville', 'Ville'),
            TextEditorField::new('description'),
            EmailField::new('Email', 'Email'),
            TelephoneField::new('Tel', 'Téléphone'),        
        ];
    }
}
