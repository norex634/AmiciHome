<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\ApplyRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentController extends AbstractController
{
    #[Route('/ajax/comments', name: 'comment_add')]
    public function add(Request $request, ApplyRepository $applyrepo, CommentRepository $commentRepo, UserRepository $userRepo, EntityManagerInterface $entityManagerInterface,TokenStorageInterface $tokenStorage): Response
    {

        // Récupérer les données du commentaire à partir de la requête
        $commentData = $request->request->all('comment');
        // Récupérer l'utilisateur actuellement connecté
        $user = $tokenStorage->getToken()->getUser();
        
        // Vérifier la validité du jeton CSRF
        if(!$this->isCsrfTokenValid('comment-add', $commentData['_token'])){
            return $this->json([
                'code' => 'INVALIDE_CSRF_TOKEN'
            ],Response::HTTP_BAD_REQUEST);
        }

        // Récupérer l'entité Apply correspondante à l'ID fourni
        $apply = $applyrepo->findOneBy(['id' => $commentData['apply']]);

        // Vérifier si l'entité Apply existe
        if(!$apply){
            return $this->json([
                'code' => 'APPLY_NOT_FOUND'
            ],Response::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle instance de l'entité Comment et lui attribuer les données du commentaire
        $comment = new Comment($apply);
        $comment->setContent($commentData['content']);
        $comment->setUser($user);

        // Persister l'entité Comment en base de données
        $entityManagerInterface->persist($comment);
        $entityManagerInterface->flush();

        $html = $this->renderView('comment/index.html.twig',[ 
            'comment' => $comment
        ]);

        // Retourner une réponse JSON avec le code de succès, le message HTML et le nombre total de commentaires pour l'entité Apply
        return $this->json([
            'code' => 'COMMENT_ADDED_SUCCESSFULLY',
            'message' => $html,
            'numberOfComments' => $commentRepo->count(['apply' => $apply])

        ]);
    }
}
