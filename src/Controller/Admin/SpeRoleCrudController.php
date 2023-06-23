<?php

namespace App\Controller\Admin;

use App\Entity\SpeRole;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SpeRoleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SpeRole::class;
    }

    public function configureFields(string $pageName): iterable
    {

        yield TextField::new('nom');
        yield AssociationField::new('image');
    }

    
}
