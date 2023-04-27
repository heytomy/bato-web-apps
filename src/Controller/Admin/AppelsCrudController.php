<?php

namespace App\Controller\Admin;

use App\Entity\Appels;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AppelsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Appels::class;
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
