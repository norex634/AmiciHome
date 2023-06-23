<?php

namespace App\Form;

use App\Entity\Spe;
use App\Entity\User;
use App\Entity\Classe;
use App\Repository\ClasseRepository;
use App\Repository\SpeRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterFullFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        
        $builder
            ->add('classe', EntityType::class,[
                'class' => Classe::class,
                'label'=>'Classe que vous joué',
                'choice_label' => "nom",
                "attr" => [
                    'class' => 'form-control',
                ],
            ])
            ->add('spe', EntityType::class,[
                'class' => Spe::class,
                'choice_label' => "nom",
                'query_builder' => function (SpeRepository $er) use ($options){
                    $classe = $options['data']->getClasse();

                    return $er->createQueryBuilder('s')
                    ->where('s.classe = :classe')
                    ->setParameter('classe', $classe);
                },
                "attr" => [
                    'class' => 'form-control',
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}