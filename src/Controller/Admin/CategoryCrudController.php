<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        
            // yield IdField::new('id');
            yield TextField::new('name');
            yield SlugField::new('slug')->setTargetFieldName('name');
            yield ColorField::new('color');

        
    }
    
}
