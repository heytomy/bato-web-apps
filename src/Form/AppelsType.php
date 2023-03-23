<?php

namespace App\Form;

use App\Entity\Appels;
use App\Entity\AppsUtilisateur;
use Doctrine\ORM\EntityManager;
use App\Entity\DefAppsUtilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AppelsType extends AbstractType
{ 
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
        $builder
        ->add('IDUtilisateur', EntityType::class,[
            'class' => DefAppsUtilisateur::class,
            'label' => 'Technicien',
            'choice_label' => function(DefAppsUtilisateur $fullname){
                return $fullname->getNom() . ' ' . $fullname->getPrenom();
            },
            'placeholder' => 'Choisissez un technicien',
            'attr' => [
                'class' => 'form-select'
            ]
        ])
            ->add('Nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez le nom du client',
                    'class' => 'form-control'
                ]
            ])
            ->add('Adr', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse du client',
                    'class' => 'form-control'
                ]
            ])
            ->add('CP', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Entrez le code postal',
                    'class' => 'form-control'
                ]
            ])
            ->add('Ville', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Entrez la ville',
                    'class' => 'form-control'
                ]
            ])
            ->add('Tel', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone',
                    'class' => 'form-control'
                ]
            ])
            ->add('Email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse email',
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez le problème',
                    'class' => 'form-control'
                ]
            ])
            ->add('rdvDate', DateType::class, [
                'label' => 'Date du rendez-vous',
                'attr' => [
                    'placeholder' => 'Entrez la date du rendez-vous',
                    'html5' => false,
                ],
                'years' => range(date('Y'), date('Y') + 5),
                'widget' => 'single_text',
            ])
            ->add('rdvHeure', TimeType::class, [
                'label' => 'Heure du rendez-vous',
                'attr' => [
                    'placeholder' => 'Entrez l\'heure du rendez-vous',
                    'class' => ''
                ],
                'widget' => 'choice',
                'minutes' => range(0, 50, 10),
                'with_seconds' => false,
                'input' => 'datetime',
                'input_format' => 'H:i:s',
                'hours' => range(7, 19),
            ])
            
            ->add('isUrgent', CheckboxType::class, [
                'label' => 'Urgent ?',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appels::class,
        ]);
    }
}
