<?php

namespace App\Form;

use App\Entity\Appels;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppelsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Adr')
            ->add('CP')
            ->add('Ville')
            ->add('Tel')
            ->add('Email')
            ->add('description')
            ->add('rdvDate')
            ->add('rdvHeure')
            ->add('isUrgent')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appels::class,
        ]);
    }
}
