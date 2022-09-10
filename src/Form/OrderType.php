<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
        ->add('address', EntityType::class, [
            'class' => Address::class,
            'multiple' => false,
            'required' => true,
            'choices' => $user->getAddresses(),
            'expanded' => true

        ])
        ->add('carrier', EntityType::class, [
            'class' => Carrier::class,
            'multiple' => false,
            'required' => true,
            'label' => 'Choisir le transporteur'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => []
        ]);
    }
}
