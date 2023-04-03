<?php

namespace App\Form;

use App\Entity\Appels;
use App\Entity\DefAppsUtilisateur;
use Symfony\Component\Form\AbstractType;
use App\Repository\DefAppsUtilisateurRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AppelsType extends AbstractType
{ 
    private $roles;

    public function __construct(DefAppsUtilisateurRepository $roles)
    {
        $this->roles = $roles;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
        $builder
        ->add('ID_Utilisateur', EntityType::class,[
            'class' => DefAppsUtilisateur::class,
            'choices' => $this->roles->findByRoleTech('ROLE_TECH_SAV'),
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
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez le nom du client',
                    'class' => 'form-control'
                ]
            ])
            ->add('Adr', TextType::class, [
                'required' => true,
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse du client',
                    'class' => 'form-control'
                ]
            ])
            ->add('CP', TextType::class, [
                'required' => true,
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Entrez le code postal',
                    'class' => 'form-control'
                ]
            ])
            ->add('Ville', TextType::class, [
                'required' => true,
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Entrez la ville',
                    'class' => 'form-control'
                ]
            ])
            ->add('Tel', TelType::class, [
                'required' => true,
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone',
                    'class' => 'form-control',
                    'pattern' => '\d+'
                ]
            ])
            
            ->add('Email', EmailType::class, [
                'required' => true,
                'label' => 'Adresse email',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse email',
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez le problème',
                    'class' => 'form-control'
                ]
            ])
            ->add('rdvDate', DateType::class, [
                'required' => true,
                'format' => 'dd-MM-yyyy',
                'label' => 'Date du rendez-vous',
                'attr' => [
                    'placeholder' => 'Entrez la date du rendez-vous',
                    'class' => 'form-control date datepicker input-group-text d-block',
                ],
                'html5' => false,
                'widget' => 'single_text',
            ])  
            ->add('rdvHeure', TimeType::class, [
                'required' => true,
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
                'required' => false,
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
