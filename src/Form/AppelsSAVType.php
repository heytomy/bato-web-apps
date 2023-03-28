<?php

namespace App\Form;

use App\Entity\AppelsSAV;
use App\Entity\ClientDef;
use App\Repository\ClientDefRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AppelsSAVType extends AbstractType
{
    private $contrats;

    public function __construct(ClientDefRepository $contrats)
    {
        $this->contrats = $contrats;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class,[
            'class' => ClientDef::class,
            'choices' => $this->contrats->findAll(),
            'label' => 'Client SAV',
            'choice_label' => function(ClientDef $nom){
                return $nom->getNom();
            },
            'placeholder' => 'Choisissez le client',
            'attr' => [
                'class' => 'form-select'
            ]])
            ->add('Nom', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez le nom du client',
                    'class' => 'form-control'
                ]
            ])
            ->add('Adr', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse du client',
                    'class' => 'form-control'
                ]
            ])
            ->add('CP', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Entrez le code postal',
                    'class' => 'form-control'
                ]
            ])
            ->add('Ville', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Entrez la ville',
                    'class' => 'form-control'
                ]
            ])
            ->add('Tel', TelType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone',
                    'class' => 'form-control',
                    'pattern' => '\d+'
                ]
            ])
            
            ->add('Email', EmailType::class, [
                'mapped' => false,
                'required' => false,
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
                'label' => 'Date du rendez-vous',
                'attr' => [
                    'placeholder' => 'Entrez la date du rendez-vous',
                ],
                'years' => range(date('Y'), date('Y') + 5),
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppelsSAV::class,
        ]);
    }
}
