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
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // Créer une nouvelle instance de l'entité Contact
        $contact = new Contact();

        // Si l'utilisateur est connecté, pré-remplir le nom et l'e-mail dans le formulaire de contact
        if($this->getUser()){
            $contact->setNom($this->getUser()->getUsername())
             ->setEmail($this->getUser()->getEmail());
        }

        // Créer le formulaire de contact avec le type ContactType et l'objet Contact
        $form = $this->createForm(ContactType::class, $contact);

        // Traiter la requête et lier les données au formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $contact = $form->getData();

            // Persister l'objet Contact en base de données
            $entityManager->persist($contact);
            $entityManager->flush();
            
            
            // email
            $email = (new Email())
            ->from($contact->getEmail())
            ->to('contact@amici-wow.be')
            ->subject($contact->getSubject())
            ->html($contact->getMessage());

            $mailer->send($email);


            // Ajouter un message flash de succès
            $this->addFlash(
                'success',
                "Votre message a bien été envoyé"
            );

            // Rediriger vers la page de contact
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
