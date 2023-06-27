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

        $commentData = $request->request->all('comment');
        $user = $tokenStorage->getToken()->getUser();
        
        if(!$this->isCsrfTokenValid('comment-add', $commentData['_token'])){
            return $this->json([
                'code' => 'INVALIDE_CSRF_TOKEN'
            ],Response::HTTP_BAD_REQUEST);
        }

        $apply = $applyrepo->findOneBy(['id' => $commentData['apply']]);

        if(!$apply){
            return $this->json([
                'code' => 'APPLY_NOT_FOUND'
            ],Response::HTTP_BAD_REQUEST);
        }

        $comment = new Comment($apply);
        $comment->setContent($commentData['content']);
        $comment->setUser($user);
        $entityManagerInterface->persist($comment);
        $entityManagerInterface->flush();

        $html = $this->renderView('comment/index.html.twig',[ 
            'comment' => $comment
        ]);

        return $this->json([
            'code' => 'COMMENT_ADDED_SUCCESSFULLY',
            'message' => $html,
            'numberOfComments' => $commentRepo->count(['apply' => $apply])

        ]);
    }
}
