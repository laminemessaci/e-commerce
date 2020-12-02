<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => false,// 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Prénom',
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,//'Votre nom',
                'attr' => [
                    'placeholder' => 'Nom',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,//'Votre mail',
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes doivent être identiques !',
                'label' => false,//'Votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => false,// 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Votre mot de passe',
                    ]
                ],
                'second_options' => [
                    'label' => false,//'Confirmez votre mot de passe',

                    'attr' => [
                        'placeholder' => 'Resaisir votre mot de passe',
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
