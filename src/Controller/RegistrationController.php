<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'Register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer, UserAuthenticatorInterface $userAuthenticatorInterface, AppAuthenticator $appAuthenticator): Response
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
            // Définir le rôle de l'utilisateur
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash(
                'success',
                "Votre compte a bien été créé"
            );

            


            // $email = (new Email())
            // ->from('admin@amici.com')
            // ->to($user->getemail())
            // ->subject('Confirmer votre compte')
            // ->html(`
            // <h1>Merci pour l'inscription</h1>
            // <p>Cliquer sur le lien pour continuer celle ci : </p>
            // <a href="#">Verifirer votre email</a>
            // `)
            // ->text("mon text");
            // $mailer->send($email);


            return $userAuthenticatorInterface->authenticateUser($user, $appAuthenticator, $request);

            
            

            
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);

    }

    


   

}