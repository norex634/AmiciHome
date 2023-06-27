<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label' => `nom`,
                "attr" => [
                    'placeholder' => 'Votre Nom',
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
            ])
            ->add('email', EmailType::class,[
                'label' => `email`,
                "attr" => [
                    'placeholder' => 'Votre Email',
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '255',
                ],
            ])
            ->add('subject', TextType::class,[
                "attr" => [
                    'placeholder' => 'Votre subject',
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '255',
                ],
                'label' => `nom`,
            ])
            ->add('message', TextareaType::class,[
                "attr" => [
                    'placeholder' => 'Votre message',
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '255',
                ],
                'label' => `nom`,
            ])
            ->add('submit', SubmitType::class, [
                'attr'=> [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Envoyer'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
