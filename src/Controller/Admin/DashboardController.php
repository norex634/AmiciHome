<?php

namespace App\Controller\Admin;

use App\Entity\Spe;
use App\Entity\User;
use App\Entity\Apply;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Classe;
use App\Entity\Comment;
use App\Entity\SpeRole;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    public function  __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ){}

    #[Route('/admin', name: 'admin')]
    #[IsGranted("ROLE_ADMIN", message: "Vous n'avez pas le droit d'accéder à cette ressource")]
    public function index(): Response
    {

        $url = $this->adminUrlGenerator->setController(ClasseCrudController::class)
        ->generateUrl();
        
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Amici');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Aller sur le site', 'fa fa-undo', 'HomePage');
        
        yield MenuItem::subMenu('Classe', 'fas fa-c')->setSubItems([
            MenuItem::linkToCrud('Toutes les classe','fas fa-list',Classe::class),
            MenuItem::linkToCrud('Ajouter','fas fa-plus',Classe::class)->setAction(Crud::PAGE_NEW)
        ]);
        
        yield MenuItem::subMenu('Spe', 'fas fa-s')->setSubItems([
            MenuItem::linkToCrud('Tous les Spe','fas fa-list',Spe::class),
            MenuItem::linkToCrud('Ajouter','fas fa-plus',Spe::class)->setAction(Crud::PAGE_NEW)
        ]);

        yield MenuItem::subMenu('SpeRole', 'fas fa-timeline')->setSubItems([
            MenuItem::linkToCrud('Tous les Spe Role','fas fa-timeline',SpeRole::class),
            MenuItem::linkToCrud('Ajouter','fas fa-plus',SpeRole::class)->setAction(Crud::PAGE_NEW)
        ]);

        yield MenuItem::subMenu('Image', 'fas fa-image')->setSubItems([
            MenuItem::linkToCrud('Toutes les Image','fas fa-images',Image::class),
            MenuItem::linkToCrud('Ajouter','fas fa-plus',Image::class)->setAction(Crud::PAGE_NEW)
        ]);

        yield MenuItem::subMenu('User', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Toutes les User','fas fa-user-friends',User::class),
            MenuItem::linkToCrud('Ajouter','fas fa-plus',User::class)->setAction(Crud::PAGE_NEW)
        ]);

        yield MenuItem::subMenu('Apply', 'fas fa-arrow-right')->setSubItems([
            MenuItem::linkToCrud('Toutes les Apply','fas fa-arrow-right',Apply::class)
        ]);

        yield MenuItem::subMenu('Comment', 'fas fa-comment')->setSubItems([
            MenuItem::linkToCrud('Toutes les Commentaires','fas fa-user-comment',Comment::class)
        ]);

        yield MenuItem::subMenu('News', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Toutes les news','fas fa-newspaper',Article::class),
            MenuItem::linkToCrud('Ajouter news','fas fa-plus',Article::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Catégories','fas fa-user-comment',Category::class)
        ]); 
    }
}
