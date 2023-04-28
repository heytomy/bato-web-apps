<?php

namespace App\Controller\Admin;

use App\Entity\ClientDef;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ClientDefCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ClientDef::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('Nom'),
            TextareaField::new('Adr'),
            TextField::new('CP'),
            TextField::new('Ville'),
            TelephoneField::new('Tel'),
            EmailField::new('EMail'),
        ];

        return $fields;
    }
}