<?php

namespace App\Form;

use App\Entity\Appels;
use App\Entity\Contrat;
use App\Entity\ClientDef;
use App\Entity\TicketUrgents;
use App\Entity\DefAppsUtilisateur;
use App\Repository\ClientDefRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\TicketUrgentsRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\DefAppsUtilisateurRepository;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class AppelsType extends AbstractType
{
    private $clientDefRepository;
    private $roles;
    private $router;
    private $security;

    public function __construct(ClientDefRepository $clientDefRepository, DefAppsUtilisateurRepository $roles, RouterInterface $router, Security $security)
    {
        $this->clientDefRepository = $clientDefRepository;
        $this->roles = $roles;
        $this->router = $router;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('isNewClient', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Nouveau Client ?',
                'attr' => [
                    'class' => 'form-check-input',
                    'data-urgent-ticket' => 'true',
                    'id' => 'isNewClient',
                ],
                'label_attr' => [
                    'for' => 'isNewClient'
                ]

                ])
            ->add('ID_Utilisateur', EntityType::class, [
                'required' => true,
                'class' => DefAppsUtilisateur::class,
                'choices' => $this->roles->findByRoleTech('ROLE_TECH_SAV'),
                'label' => 'Technicien',
                'choice_label' => function (DefAppsUtilisateur $fullname) {
                    return $fullname->getNom() . ' ' . $fullname->getPrenom();
                },
                'placeholder' => 'Choisissez un technicien',
                'attr' => [
                    'class' => 'form-select'
                ],
                'constraints' => [
                    new Callback(function ($value, ExecutionContextInterface $context) use ($user) {
                        if (!$user || !$this->security->isGranted('ROLE_TECH_SAV')) {
                            $context->buildViolation('Seul les utilisateurs possèdant le rôle ROLE_TECH_SAV peuvent être choisi.')
                                ->addViolation();
                        }
                    }),
                    new NotBlank(['message' => 'Veuillez sélectionner un technicien'])
                ]
            ])
            // Champs séléctionnant uniquement les clients ayant un contrat d'entretion
            // ->add('ClientList', EntityType::class, [
            //     'mapped' => false,
            //     'class' => ClientDef::class,
            //     'choices' => $this->clientDefRepository->findByClientWithContrats(),
            //     'label' => 'Client SAV',
            //     'choice_label' => function (ClientDef $client) {
            //         return $client->getNom();
            //     },
            //     'placeholder' => 'Choisissez le client',
            //     'attr' => [
            //         'class' => 'form-select',
            //         'data-contrats-url' => $this->router->generate('get_client_and_contrats_info', ['id' => '__clientId__']),
            //     ],
            // ])
            
            ->add('ClientList', EntityType::class, [
                'required' => false,
                'mapped' => false,
                'class' => ClientDef::class,
                'choices' => $this->clientDefRepository->findAll(),
                'label' => 'Clients',
                'choice_label' => function (ClientDef $client) {
                    return $client->getNom();
                },
                'placeholder' => 'Choisissez le client',
                'attr' => [
                    'class' => 'form-select',
                    'data-contrats-url' => $this->router->generate('get_client_and_contrats_info', ['id' => '__clientId__']),
                ],
            ]) 

            ->add('Nom', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez le nom du client',
                    'class' => 'form-control'
                ]
            ])
            ->add('CodeContrat', EntityType::class, [
                'required' => false,
                'placeholder' => 'Choisissez le client pour voir les contrats',
                'class' => Contrat::class,
                'label' => 'Code Contrat',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'contrats-field',
                    'readonly' => 'readonly',
                ],
                'choice_label' => 'libelle',
            ])
            ->add('CodeClient', EntityType::class, [
                'required' => false,
                'class' => ClientDef::class,
                'placeholder' => 'Code Client',
                'label' => 'Code Client',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'client-field',
                    'readonly' => 'readonly',
                ],
                'choice_label' => 'id',
            ])
            ->add('Adr', TextType::class, [
                'required' => true,
                'label' => 'Adresse',
                'empty_data' => null,
                'attr' => [
                    'placeholder' => 'Adresse du client',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une adresse']),
                    new Length(['max' => 50, 'maxMessage' => 'L\'adresse ne doit pas dépasser {{ limit }} caractères']),
                ],
            ])
            ->add('CP', TextType::class, [
                'required' => true,
                'label' => 'Code postal',
                'empty_data' => null,
                'attr' => [
                    'placeholder' => 'Code postal',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un code postal']),
                    new Regex(['pattern' => '/^\d{5}$/i', 'message' => 'Le code postal doit être composé de 5 chiffres']),
                ],
            ])
            ->add('Ville', TextType::class, [
                'required' => true,
                'label' => 'Ville',
                'empty_data' => null,
                'attr' => [
                    'fieldset' => false,
                    'placeholder' => 'Ville',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une ville']),
                    new Length(['max' => 25, 'maxMessage' => 'La ville ne doit pas dépasser {{ limit }} caractères']),
                ],
            ])
            ->add('Tel', TelType::class, [
                'required' => true,
                'label' => 'Numéro de téléphone',
                'empty_data' => null,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un numéro de téléphone']),
                    new Regex(['pattern' => '/^0[1-9]([-. ]?\d{2}){4}$/', 'message' => 'Veuillez saisir un numéro de téléphone valide']),
                ],
            ])
            ->add('Email', EmailType::class, [
                'required' => true,
                'label' => 'Adresse email',
                'empty_data' => null,
                'attr' => [
                    'placeholder' => 'Entrez votre adresse email',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer l\'adresse email du client.']),
                    new Email(['message' => 'L\'adresse email "{{ value }}" n\'est pas valide.'])
                ]
            ])
            ->add('description', TinymceType::class, [
                'required' => true,
                'label' => 'Description',
                'attr' => [
                    'selector' => 'textarea',
                    'toolbar' => 'undo redo | copy cut paste',
                    'menubar' => 'false',                
                    'contextmenu' => 'false',
                    'placeholder' => 'Décrivez le problème rencontré par le client',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer une description du problème rencontré par le client.']),
                ]
            ])

            ->add('rdvDateTime', DateTimeType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Date et heure du rendez-vous :',
                'widget' => 'single_text',
                // 'format' => 'dd-MM-yyyy HH:mm',
                'attr' => [
                    'class' => 'form-control datetimepicker',
                    'placeholder' => 'Sélectionnez une date et heure de RDV',
                ],
                'html5' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'Un rendez-vous ne peut pas être placé à une date antérieure !',
                    ]),
                    new Assert\Callback(function ($dateTime, ExecutionContextInterface $context) {
                        $time = $dateTime->format('H:i');
                        if ($time < '07:00' || $time > '20:00') {
                            $context->buildViolation('L\'heure de rendez-vous doit être comprise entre 7:00 et 20:00')
                                ->atPath('rdvDateTime')
                                ->addViolation();
                        }
                    })
                ],
            ]) 

            ->add('rdvDateTimeFin', DateTimeType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Fin de rendez-vous prévu :',
                'widget' => 'single_text',
                // 'format' => 'dd-MM-yyyy HH:mm',
                'attr' => [
                    'class' => 'form-control datetimepicker',
                    'placeholder' => 'Sélectionnez une date et heure de fin de RDV',
                ],
                'html5' => true,
            ]) 

            ->add('allDay', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Toute la journée ?',
                'attr' => [
                    'class' => 'form-check-input',
                    'data-all-day' => 'true',
                    'id' => 'allDayCheckbox'
                ],
                'label_attr' => [
                    'class' => 'form-check-label',
                    'for' => 'allDayCheckbox'
                ]
            ])

            ->add('isUrgent', CheckboxType::class, [
                'required' => false,
                'label' => 'Rendez-vous urgent ?',
                'attr' => [
                    'class' => 'form-check-input',
                    'data-urgent-ticket' => 'true',
                ],
                'label_attr' => [
                    'class' => 'form-check-label',
                ]
                ])

            ->add('status', ChoiceType::class, [
                'mapped' => false,
                'required' => false,
                'placeholder' => 'Niveau d\'urgence',
                'label' => false,
                'choices' => [
                    'Faible' => 0,
                    'Moyen'  => 1,
                    'Elevé'  => 2,
                ],
                'attr' => [
                    'class' => 'form-control urgency-select d-none',
                ],
                ]);    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appels::class,
        ]);
    }
}
