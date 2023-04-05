<?php

namespace App\Form;

use App\Entity\Calendrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('allDay', CheckboxType::class,[
                'required' => false,
                'label' => 'Cet évènement est prévu pour toute la journée ?',
            ])
            ->add('dateDebut', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('dateFin', DateTimeType::class, [
                'mapped' => false,
                'required' => false,
                'date_widget' => 'single_text'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }
}
