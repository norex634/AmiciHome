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

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        // Créer un formulaire avec le type RegisterFullFormType et l'utilisateur actuel
        $form = $this->createForm(RegisterFullFormType::class, $user);
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Définir le rôle de l'utilisateur sur "ROLE_MEMBRE"
            $user->setRoles(['ROLE_MEMBRE']);

            // Persister l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();
            
            // Ajouter un message flash de succès
            $this->addFlash(
                'success',
                "Votre compte a bien été finalisé"
            );
            
            // Authentifier et connecter l'utilisateur automatiquement
            return $userAuthenticatorInterface->authenticateUser($user, $appAuthenticator, $request);
        }

        return $this->render('registration/registerfull.html.twig', [
            'registrationFullForm' => $form->createView(),
            'user' => $user
        ]);

    }

    


   

}