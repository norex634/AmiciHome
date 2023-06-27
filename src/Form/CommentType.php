<?php

namespace App\Form;

use App\Entity\Apply;
use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class,[
                'label'=>'Votre message',
                
                ])

            ->add('apply', HiddenType::class)   

            ->add('send', SubmitType::class,[
                'label'=>'Envoyer',
            ]) ;
            
            $builder->get('apply')
                ->addModelTransformer(new CallbackTransformer(
                    fn (Apply $apply)=>$apply->getId(),
                    fn (Apply $apply)=>$apply->getNom(),
                ));
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_token_id' => 'comment-add'
        ]);
    }
}