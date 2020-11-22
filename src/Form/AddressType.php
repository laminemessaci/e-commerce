<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> "Nom d'adresse*",
                'attr'=> [
                    'placeholder'=>'Nommez votre adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label'=> 'Prénom*',
                'attr'=> [
                    'placeholder'=>'Entrez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label'=> 'Nom*',
                'attr'=> [
                    'placeholder'=>'Entrez votre nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label'=> 'Société',
                'attr'=> [
                    'placeholder'=>'(facultatif) Entrez votre nom se société)'
                ]
            ])
            ->add('address', TextType::class, [
                'label'=> 'Adresse*',
                'attr'=> [
                    'placeholder'=>'N°, Nom Rue '
                ]
            ])
            ->add('postal', TextType::class, [
                'label'=> 'Code postal*',
                'attr'=> [
                    'placeholder'=>'Code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label'=> 'Ville*',
                'attr'=> [
                    'placeholder'=>'Paris'
                ]
            ])
            ->add('country', CountryType::class, [
                'label'=> 'Pays*',
                'attr'=> [
                    'placeholder'=>'Pays'
                ]
            ])
            ->add('phone', TelType::class, [
                'label'=> 'Numéro de téléphone*',
                'attr'=> [
                    'placeholder'=>'Numéro de tel'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=> 'Valider',
                'attr'=>[
                    'class'=> 'btn btn-primary '
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
