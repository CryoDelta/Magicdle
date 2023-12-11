<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColorIdentityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'placeholder' => 'Choose an option',
                'choices' => [
                    'Black' => 'B',
                    'Green' => 'G',
                    'Red' => 'R',
                    'Blue' => 'U',
                    'White' => 'W',
                    'Colorless' => ''
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => false
            ])
            ->add('mode', ChoiceType::class, [
                'placeholder' => 'Choose an option',
                'choices' => [
                    'Exactly' => 1,
                    'Including' => 2,
                    'At most' => 3
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
