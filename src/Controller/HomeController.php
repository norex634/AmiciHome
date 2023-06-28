<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\ClasseRepository;
use App\Repository\SpeRepository;
use App\Repository\SpeRoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'HomePage')]
    public function index(UserRepository $userRepo, SpeRepository $speRepo, SpeRoleRepository $speRoleRepo, ArticleRepository $articleRepo, CategoryRepository $categoryRepo): Response
    {

        // Récupérer tous les utilisateurs à partir du UserRepository
        $user = $userRepo->findAll();
        // Récupérer tous les Spes à partir du SpeRepository
        $spe = $speRepo->findAll();
        // Récupérer tous les SpeRoles à partir du SpeRoleRepository
        $speRole = $speRoleRepo->findAll();
        // Récupérer le dernier article de la catégorie "News" à partir de l'ArticleRepository
        $newsArticle = $articleRepo->findLatestArticleByCategory('News');
        // Récupérer le dernier article de la catégorie "Recrutement" à partir de l'ArticleRepository
        $recruitmentArticle = $articleRepo->findLatestArticleByCategory('Recrutement');
        

        return $this->render('home/index.html.twig', [
            'users' => $user,
            'spes' => $spe,
            'speRoles'=> $speRole,
            'newsArticle' => $newsArticle,
            'recruitmentArticle' => $recruitmentArticle

            
        ]);
    }
}
