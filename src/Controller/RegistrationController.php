<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use App\Form\RegisterFullFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'Register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {

        
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
           
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //user role
            $user->setRoles(['ROLE_MEMBRE']);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email


            $email = (new Email())
            ->from('admin@amici.com')
            ->to($user->getemail())
            ->subject('Confirmer votre compte')
            ->html(`
            <h1>Merci pour l'inscription</h1>
            <p>Cliquer sur le lien pour continuer celle ci : </p>
            <a href="#">Verifirer votre email</a>
            `)
            ->text("mon text");
            $mailer->send($email);

            return $this->redirectToRoute('HomePage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);

    }

    #[Route('/registerfull', name: 'Registerfull')]
    public function registerfull(Request $request, EntityManagerInterface $entityManager, UserRepository $user ): Response
    {

        
        $user = $this->getUser();
        $form = $this->createForm(RegisterFullFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('HomePage');
        }

        return $this->render('registration/registerfull.html.twig', [
            'registrationFullForm' => $form->createView(),
            'user' => $user
        ]);

    }

    


   

}