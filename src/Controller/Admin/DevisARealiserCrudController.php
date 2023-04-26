<?php

namespace App\Controller\Admin;

use App\Entity\DevisARealiser;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DevisARealiserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DevisARealiser::class;
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
