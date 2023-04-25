<?php

namespace App\Controller\Admin;

use App\Entity\ChantierApps;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ChantierAppsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ChantierApps::class;
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
