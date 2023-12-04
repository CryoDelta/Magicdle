<?php

namespace App\Form;

use App\Entity\Set;
use App\Repository\SetRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('submit', SubmitType::class, [
                'label' => '<i class="bi bi-check"></i> Apply',
                'label_html' => true
            ])
            ->add('color', ColorIdentityType::class, [
                'label' => 'Color',
                'required' => false
            ])
            ->add('typeLine', TextType::class, [
                'label' => 'Type',
                'required' => false
            ])
            ->add('set', EntityType::class, [
                'placeholder' => 'Choose an option',
                'label' => 'Set',
                'class' => Set::class,
                'query_builder' => function(SetRepository $setRepository) {
                    return $setRepository
                        ->createQueryBuilder('s')
                        ->orderBy('s.releaseDate', 'DESC')
                        ;
                },
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('effectText', TextType::class, [
                'label' => 'Effect',
                'required' => false
            ])
            ->add('flavorText', TextType::class, [
                'label' => 'Flavor',
                'required' => false
            ])
            ->add('stats', StatType::class, [
                'label' => 'Stats',
                'required' => false
            ])
            ->add('rarity', ChoiceType::class, [
                'placeholder' => 'Choose an option',
                'label' => 'Rarity',
                'choices' => [
                    'Common' => 'C',
                    'Uncommon' => 'U',
                    'Rare' => 'R',
                    'Mythic' => 'M'
                ],
                'required' => false
            ])
            ->add('artist', TextType::class, [
                'label' => 'Artist',
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
