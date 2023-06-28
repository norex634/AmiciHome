<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'error')]
    public function show(FlattenException $exception, Environment $env): Response
    {
        // Déterminer le nom de la vue en fonction du code de statut de l'exception
        $view = "bundles/TwigBundle/Exception/error{$exception->getStatusCode()}.html.twig";
        // Vérifier si la vue spécifique au code de statut existe
        if (!$env->getLoader()->exists($view)){
            // Utiliser la vue générale si la vue spécifique n'existe pas
            $view = "bundles/TwigBundle/Exception/error.html.twig";
        }

        return $this->render($view);
    }
}
