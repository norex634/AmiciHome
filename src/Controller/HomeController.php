<?php

namespace App\Controller;

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
    public function index(UserRepository $userRepo): Response
    {

        $user = $userRepo->findAll();
        //$spe = $speRepo->findAll();
        //$speRole = $speRoleRepo->findAll();

        return $this->render('home/index.html.twig', [
            'users' => $user,
            // 'spes' => $spe,
            // 'speRoles'=> $speRole
            
        ]);
    }
}
