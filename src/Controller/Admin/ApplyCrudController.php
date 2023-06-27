<?php

namespace App\Controller\Admin;

use App\Entity\Apply;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ApplyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Apply::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {

        yield AssociationField::new('User');
        yield TextField::new('nom');
        yield TextField::new('log');
        yield TextField::new('about');
        yield TextField::new('why');
        yield TextField::new('exp');
        yield TextField::new('btag');
        yield TextField::new('discord');
        
    }
}
