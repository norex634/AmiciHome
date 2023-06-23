<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        
        $classeDir = $this->getParameter('classe_directory');
        $uploadsDir = $this->getParameter('uploads_directory');

         
         $imageField = ImageField::new('path')
            ->setBasePath($uploadsDir)
            ->setUploadDir($classeDir)
            ->setUploadedFileNamePattern('[slug]-[uuid].[extension]');
            
            if (Crud::PAGE_EDIT == $pageName) {
                $imageField->setRequired(false);
            }
            yield $imageField;
        
    }
    
}
