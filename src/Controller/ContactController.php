<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer ): Response
    {
        $contact = new Contact();

        if($this->getUser()){
            $contact->setNom($this->getUser()->getUsername())
             ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $entityManager->persist($contact);
            $entityManager->flush();
            
            
            // email
            $email = (new Email())
            ->from($contact->getEmail())
            ->to('Admin@amici.com')
            ->subject($contact->getSubject())
            ->html($contact->getMessage());

            $mailer->send($email);


            
            $this->addFlash(
                'success',
                "Votre message a bien été envoyé"
            );

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
