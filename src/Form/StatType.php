<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'placeholder' => 'Choose an option',
                'choices' => [
                    'Mana value' => '',
                    'Power' => '',
                    'Toughness' => '',
                    'Loyalty' => ''
                ],
                'expanded' => false,
                'multiple' => false
            ])
            ->add('operator', ChoiceType::class, [
                'placeholder' => 'Choose an option',
                'choices' => [
                    'Equals' => '=',
                    'Is less than' => '<',
                    'Is more than' => '>'
                ],
                'expanded' => false,
                'multiple' => false
            ])
            ->add('value', NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
