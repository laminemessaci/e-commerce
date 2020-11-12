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
            ->add('firstname',TextType::class, [
                'label'=> 'Votre prénom',
                'constraints' => new Length(2, 25),
                'attr'=>[
                    'placeholder'=>'John'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label'=> 'Votre nom',
                'constraints' => new Length(2, 25),
                'attr'=>[
                    'placeholder'=>'Doe'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> 'Votre mail',
                'constraints' => new Length(2, 60),
                'attr'=>[
                    'placeholder'=>'john.doe@exemple.com'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'invalid_message'=>'Les mots de passes doivent être identiques !',
                'label'=> 'Votre mot de passe',
                'required'=> true,
                'first_options'=>['label' => 'Mot de passe'],
                'second_options'=>['label' => 'Confirmez votre mot de passe']
            ])

            ->add('submit', SubmitType::class, [
                'label'=>"S'inscrire"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
