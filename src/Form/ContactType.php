<?php

namespace App\Form;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, [
                'label'=> false,
                'attr'=>[
                    'placeholder'=>'Votre prénom'
                ]
            ])
            ->add('nom', TextType::class, [
                'label'=> false,
                'attr'=>[
                    'placeholder'=>'Votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> false,
            'attr'=>[
        'placeholder'=>'Votre adresse email'
    ]
            ])
            ->add('contenu', TextareaType::class, [
                'label'=> false,
                'attr'=>[
                    'placeholder'=>'Votre message'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=> 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
