<?php

namespace App\Controller;

use App\Form\RegisterFullFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegisterFullController extends AbstractController
{

    #[Route('/Registerfull', name: 'Registerfull')]
    #[IsGranted("ROLE_USER", message: "Vous n'avez pas le droit d'accéder à cette ressource")]
    public function registerfull(Request $request, EntityManagerInterface $entityManager, UserRepository $user, UserAuthenticatorInterface $userAuthenticatorInterface, AppAuthenticator $appAuthenticator ): Response
    {

        
        $user = $this->getUser();
        $form = $this->createForm(RegisterFullFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //user role
            $user->setRoles(['ROLE_MEMBRE']);

            
            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash(
                'success',
                "Votre compte a bien été finalisé"
            );
            
            return $userAuthenticatorInterface->authenticateUser($user, $appAuthenticator, $request);
        }

        return $this->render('registration/registerfull.html.twig', [
            'registrationFullForm' => $form->createView(),
            'user' => $user
        ]);

    }

    


   

}