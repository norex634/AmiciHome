<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {

        yield TextField::new('username');
        yield TextField::new('password');
        yield ChoiceField::new('roles')->setChoices([
            // $value => $badgeStyleName
            '[ADMIN]' => 'success',
            '[ROOSTER]' => 'warning',
            '[MEMBRE]' => 'danger',
        ]);
        yield TextareaField::new('description');
        yield AssociationField::new('classe');
        yield AssociationField::new('spe');
    }
}
