<?php

namespace App\Form;

use App\Entity\Apply;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ApplyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        
        $builder
            ->add('nom', TextType::class,[
                'label' => `Nom`,
                "attr" => [
                    'placeholder' => 'Nom de votre personnage ',
                    'class' => 'form-control',
                ],
            ])
            ->add('log', TextType::class,[
                'label' => `log`,
                "attr" => [
                    'placeholder' => 'Le lien warcraftlog de votre personnage',
                    'class' => 'form-control',
                ],
            ])
            ->add('about', TextareaType::class,[
                'label' => `about`,
                "attr" => [
                    'class' => 'form-control',
                    'placeholder' => "Parlez-nous un peu de vous! L'âge, ce que vous faites dans la vie et vos intérêts en dehors de jouer à WoW",
                ],
            ])
            ->add('why', TextareaType::class,[
                'label' => `why`,
                "attr" => [
                    'class' => 'form-control',
                    'placeholder' => "Qu'est ce qu'il vous plait chez Amici?",
                ],
            ])
            ->add('exp', TextareaType::class,[
                'label' => `exp`,
                "attr" => [
                    'class' => 'form-control',
                    'placeholder' => "Résumez votre expérience de raid et votre historique de guildes",
                ],
            ])
            ->add('btag', TextType::class,[
                'label' => `btag`,
                "attr" => [
                    'placeholder' => 'Votre Battletag',
                    'class' => 'form-control',
                ],
            ])
            ->add('discord', TextType::class,[
                'label' => `discord`,
                "attr" => [
                    'placeholder' => 'Votre ID Discord',
                    'class' => 'form-control',
                ],
            ])
            
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Apply::class,
        ]);
    }
}