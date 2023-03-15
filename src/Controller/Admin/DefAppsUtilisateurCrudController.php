<?php

namespace App\Controller\Admin;

use App\Entity\DefAppsUtilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{CollectionField, IdField, EmailField, TextField, TelephoneField, TextareaField};
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};

class DefAppsUtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DefAppsUtilisateur::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL);
    }


    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('Nom'),
            TextField::new('Prenom'),
            TextareaField::new('Adresse'),
            TextField::new('CP'),
            TextField::new('Ville'),
            TelephoneField::new('Tel_1'),
            TelephoneField::new('Tel_2'),
            EmailField::new('Mail'),
        ];

        return $fields;
    }
}
