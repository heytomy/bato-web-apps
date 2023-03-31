<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\AppelsSAV;
use App\Entity\ClientDef;
use App\Entity\DefAppsUtilisateur;
use App\Repository\ClientDefRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\DefAppsUtilisateurRepository;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AppelsSAVType extends AbstractType
{
    private $clientDefRepository;
    private $roles;
    private $router;

    public function __construct(ClientDefRepository $clientDefRepository, DefAppsUtilisateurRepository $roles, RouterInterface $router )
    {
        $this->clientDefRepository = $clientDefRepository;
        $this->roles = $roles;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ID_Utilisateur', EntityType::class, [
                'class' => DefAppsUtilisateur::class,
                'choices' => $this->roles->findByRoleTech('ROLE_TECH_SAV'),
                'label' => 'Technicien',
                'choice_label' => function (DefAppsUtilisateur $fullname) {
                    return $fullname->getNom() . ' ' . $fullname->getPrenom();
                },
                'placeholder' => 'Choisissez un technicien',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('client', EntityType::class, [
                'required' => true,
                'class' => ClientDef::class,
                'choices' => $this->clientDefRepository->findByClientWithContrats(),
                'label' => 'Client SAV',
                'choice_label' => function (ClientDef $client) {
                    return $client->getNom()
                    . ' ' . $client->getContrats()[0]->getId() . ' ' . $client->getId();
                },
                'placeholder' => 'Choisissez le client',
                'attr' => [
                    'class' => 'form-select',
                    'data-contrats-url' => $this->router->generate('get_client_and_contrats_info', ['id' => '__clientId__']),
                ]
            ])            
            ->add('Nom', HiddenType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez le nom du client',
                    'class' => 'form-control'
                ]
            ])
            ->add('Contrats', ChoiceType::class, [
                'required' => true,
                'choices' => [],
                'label' => 'Code Contrat',
                'attr' => [
                    'placeholder' => 'Code Contrat',
                    'class' => 'form-control',
                    'id' => 'contrats-field'
                ]
            ])
            ->add('Client', TextType::class, [
                'required' => true,
                'label' => 'Code Client',
                'attr' => [
                    'disabled' => true,
                    'placeholder' => 'Code Client',
                    'class' => 'form-control'
                ]
            ])
            ->add('Adr', TextType::class, [
                'required' => true,
                'label' => 'Adresse',
                'empty_data' => null,
                'attr' => [
                    'placeholder' => 'Adresse du client',
                    'class' => 'form-control'
                ]
            ])
            ->add('CP', TextType::class, [
                'required' => true,
                'label' => 'Code postal',
                'empty_data' => null,
                'attr' => [
                    'placeholder' => 'Code postal',
                    'class' => 'form-control'
                ]
            ])
            ->add('Ville', TextType::class, [
                'required' => true,
                'label' => 'Ville',
                'empty_data' => null,
                'attr' => [
                    'fieldset' => false,
                    'placeholder' => 'Ville',
                    'class' => 'form-control'
                ]
            ])
            ->add('Tel', TelType::class, [
                'required' => true,
                'label' => 'Numéro de téléphone',
                'empty_data' => null,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone',
                    'class' => 'form-control',
                    'pattern' => '\d+',
                ]
            ])

            ->add('Email', EmailType::class, [
                'required' => true,
                'label' => 'Adresse email',
                'empty_data' => null,
                'attr' => [
                    'placeholder' => 'Entrez votre adresse email',
                    'class' => 'form-control',
                                    ]
            ])
            ->add('description', TinymceType::class, [
                'required' => true,
                'label' => 'Description',
                'attr' => [
                    "toolbar" => "bold italic underline | bullist numlist",
                    'placeholder' => 'Décrivez le problème rencontré par le client',
                ]
            ])
            ->add('rdvDate', DateType::class, [
                'required' => true,
                'label' => 'Date du rendez-vous',
                'attr' => [
                    'placeholder' => 'Entrez la date du rendez-vous',
                    'class' => 'form-control date datepicker input-group-text d-block',
                ],
                'html5' => false,
                'format' => 'dd-MM-yyyy',
                'widget' => 'single_text',
            ])            
            ->add('rdvHeure', TimeType::class, [
                'required' => true,
                'label' => 'Heure du rendez-vous',
                'attr' => [
                    'placeholder' => 'Entrez l\'heure du rendez-vous',
                    'class' => 'form-control timepicker input-group-text d-block'
                ],
                'html5' => false,
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
                'attr' => [
                    'data-urgent-ticket' => 'true'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppelsSAV::class,
        ]);
    }
}
