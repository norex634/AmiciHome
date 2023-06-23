<?php

namespace App\Controller\Admin;

use App\Entity\Spe;
use App\Entity\Classe;
use App\Repository\ClasseRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Validator\Constraints\Choice;

class SpeCrudController extends AbstractCrudController
{
    

    public static function getEntityFqcn(): string
    {
        return Spe::class;
    }

    public function configureFields(string $pageName): iterable
    {

        yield TextField::new('nom');
        yield AssociationField::new('image');
        yield AssociationField::new('classe');
        yield AssociationField::new('speRole');
    }

   
}
