<?php

namespace App\Form;

use App\Entity\Spe;
use App\Entity\User;
use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        
        $builder
            ->add('username', TypeTextType::class,[
                'label' => `nom d'utilisateur`,
                "attr" => [
                    'class' => 'form-control',
                ],
            ])
            ->add('email', EmailType::class,[
                'label' => `Email`,
                "attr" => [
                    'class' => 'form-control',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'label'=>'Password',
                    "attr" => [
                        'class' => 'form-control',
                    ],
                ],
                'second_options' => [
                    'label'=>'Confirmer le mot de passe',
                    "attr" => [
                        'class' => 'form-control',
                    ],
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('classe', EntityType::class,[
                'class' => Classe::class,
                'label'=>'Classe que vous joué',
                'choice_label' => "nom",
                "attr" => [
                    'class' => 'form-control',
                ],
            ])
            // ->add('spe', EntityType::class,[
            //     'class' => Spe::class,
            //     'choice_label' => "nom",
            //     "attr" => [
            //         'class' => 'form-control',
            //     ],
            // ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}