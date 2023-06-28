<?php

namespace App\Controller;

use App\Entity\Apply;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\ApplyFormType;
use App\Security\AppAuthenticator;
use App\Repository\ApplyRepository;
use App\Service\ApplyService;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApplyController extends AbstractController
{
    #[Route('/applyng', name: 'applyng')]
    #[IsGranted("ROLE_MEMBRE", message: "Vous n'avez pas le droit d'accéder à cette ressource")]
    public function index(Request $request, EntityManagerInterface $entityManager, ApplyRepository $apply, UserAuthenticatorInterface $userAuthenticatorInterface, AppAuthenticator $appAuthenticator, TokenStorageInterface $tokenStorage): Response
    {

        // Action pour créer une nouvelle candidature
        $apply = new Apply();
        $user = $tokenStorage->getToken()->getUser();
        // Créer le formulaire de candidature avec le type ApplyFormType et l'objet Apply
        $form = $this->createForm(ApplyFormType::class, $apply);
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
             // Générer un slug aléatoire pour l'apply
            $slugrand = rand(100000,999999999999);
            $apply->setSlug($slugrand);
            $apply->setUser($user); // Assurez-vous de définir l'utilisateur dans votre entité Apply
            // Persister l'objet Apply en base de données
            $entityManager->persist($apply);
            $entityManager->flush();
            
            // Ajouter un message flash de succès
            $this->addFlash(
                'success',
                "Votre apply a bien été envoyer"
            );
            
            return $userAuthenticatorInterface->authenticateUser($user, $appAuthenticator, $request);
        }

        return $this->render('apply/index.html.twig', [
            'ApplyForm' => $form->createView(),
        ]);

    }


    #[Route('/apply', name: 'ApplyShowAll')]
    #[IsGranted("ROLE_ROOSTER", message: "Vous n'avez pas le droit d'accéder à cette ressource")]
    public function showAll(ApplyRepository $applyRepo, ApplyService $applyService): Response
    {
        // Action pour afficher toutes les candidatures
        // $apply = $applyRepo->findAll();

        return $this->render('apply/showall.html.twig', [
            // 'applys' => $apply,
            // Récupérer toutes les candidatures à partir du dépôt ApplyRepository ou d'un service ApplyService
            'applys' => $applyService->getPaginatedApply(),
        ]);
    }

    #[Route('/apply{slug}', name: 'ApplyShow')]
    #[IsGranted("ROLE_ROOSTER", message: "Vous n'avez pas le droit d'accéder à cette ressource")]
    public function show(Apply $apply ): Response
    {
        // Action pour afficher une candidature spécifique

        // Vérifier si l'utilisateur possède le rôle 'ROLE_ROOSTER', sinon refuser l'accès
        $this->denyAccessUnlessGranted('ROLE_ROOSTER');

        // Créer un nouveau commentaire lié à la candidature spécifique
        $comment = new Comment($apply);

        // Créer un formulaire de commentaire en utilisant le type de formulaire CommentType et le commentaire créé
        $commentForm = $this->createForm(CommentType::class, $comment);

        return $this->render('apply/show.html.twig', [
            
            'apply' => $apply,
            'commentForm' => $commentForm
        ]);
    }

    
}
