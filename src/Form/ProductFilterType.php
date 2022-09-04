<?php

namespace App\Form;

use App\Entity\Category;
use App\Classe\ProductFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;

class ProductFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('categories', EntityType::class, [
            'class' => Category::class,
            'label' => false,
            'multiple' => true,
            'expanded' => true,
            'required' => false
        ])
        ->add('prices', ChoiceType::class, [
            'choices' => [
                'Moins de 10 €' => 1,
                'De 10 à 50 €' => 2,
                'Plus de 50 €' => 3,
            ],
            'expanded' => true,
            'multiple' => true,
            'required' => false
        ])
        ->add('statement', ChoiceType::class, [
            'choices' => [
                'Tout' => null,
                'Neuf' => 1,
                'Occasion' => 2           
            ],
            'preferred_choices' => [null],
            'expanded' => true
            
            
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFilter::class,
            'method' => 'POST',
            'crsf_protection' => false
            // Configure your form options here
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
