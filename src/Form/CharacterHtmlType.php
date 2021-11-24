<?php

namespace App\Form;

use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CharacterHtmlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false
            ])
            ->add('surname',  TextType::class, [
                'required' => true
            ])
            ->add('caste',  TextType::class, [
                'help' => 'Caste du personnage'
            ])
            ->add('knowledge',  TextType::class, [
                'required' => false
            ])
            ->add('intelligence', IntegerType::class, [
                'required' => false, 
                'help' => 'Niveau d\'intelligence du personnage (1-250)',
                'attr' => [
                    'min' => 1,
                    'max' => 250
                ]
            ])
            ->add('life', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 250,
                    'placeholder' => 'Niveau de vie du personnage (1-250)'
                ]
            ])
            ->add('image', TextType::class, [
                'required' => false
            ])
            ->add('kind', ChoiceType::class, [
                'choices'  => [
                    'Dame' => 'Dame',
                    'Seigneur' => 'Seigneur',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}