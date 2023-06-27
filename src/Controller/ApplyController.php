<?php

namespace App\Controller;

use App\Entity\Apply;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\ApplyFormType;
use App\Security\AppAuthenticator;
use App\Repository\ApplyRepository;
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

        $apply = new Apply();
        $user = $tokenStorage->getToken()->getUser();
        $form = $this->createForm(ApplyFormType::class, $apply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugrand = rand(100000,999999999999);
            $apply->setSlug($slugrand);
            $apply->setUser($user); // Assurez-vous de définir l'utilisateur dans votre entité Apply
            $entityManager->persist($apply);
            $entityManager->flush();
            
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
    public function showAll(ApplyRepository $applyRepo): Response
    {
        $apply = $applyRepo->findAll();

        return $this->render('apply/showall.html.twig', [
            'applys' => $apply,
        ]);
    }

    #[Route('/apply{slug}', name: 'ApplyShow')]
    #[IsGranted("ROLE_ROOSTER", message: "Vous n'avez pas le droit d'accéder à cette ressource")]
    public function show(Apply $apply): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ROOSTER');

        $comment = new Comment($apply);

        $commentForm = $this->createForm(CommentType::class, $comment);

        return $this->render('apply/show.html.twig', [
            
            'apply' => $apply,
            'commentForm' => $commentForm
        ]);
    }

    
}
