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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('rdvDateTime', DateTimeType::class, [
                'required' => true,
                'label' => 'Date et heure du rendez-vous',
                'attr' => [
                    'placeholder' => 'Entrez la date et l\'heure du rendez-vous',
                    'class' => 'form-control datetimepicker input-group-text d-block',
                ],
                'html5' => false,
                'format' => 'dd-MM-yyyy HH:mm',
                'widget' => 'single_text',
                'input' => 'datetime',
                'input_format' => 'yyyy-MM-dd HH:mm:ss',
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
