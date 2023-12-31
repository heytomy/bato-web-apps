<?php

namespace App\Form;

use App\Entity\Appels;
use App\Entity\Contrat;
use App\Entity\ClientDef;
use App\Entity\AppsUtilisateur;
use App\Repository\ClientDefRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\AppsUtilisateurRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Routing\RouterInterface;
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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AppelsEditType extends AbstractType
{
    private $clientDefRepository;
    private $roles;
    private $router;
    private $security;

    public function __construct(
        ClientDefRepository $clientDefRepository,
        AppsUtilisateurRepository $roles,
        RouterInterface $router,
        Security $security
    ) {
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
                'class' => AppsUtilisateur::class,
                'choices' => $this->roles->findByRoleTech('ROLE_TECH_SAV'),
                'label' => 'Technicien',
                'choice_label' => function (AppsUtilisateur $username) {
                    return $username->getIDUtilisateur()->getNom() . ' ' . $username->getIDUtilisateur()->getPrenom();
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

            ->add('Nom', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez le nom du client',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le nom du client']),
                ],
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
                'required' => false,
                'label' => 'Numéro de téléphone',
                'empty_data' => null,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    // new NotBlank(['message' => 'Veuillez saisir un numéro de téléphone']),
                    new Regex(['pattern' => '/^0[1-9]([-. ]?\d{2}){4}$/', 'message' => 'Veuillez saisir un numéro de téléphone valide']),
                ],
            ])
            ->add('Email', EmailType::class, [
                'required' => false,
                'label' => 'Adresse email',
                'empty_data' => null,
                'attr' => [
                    'placeholder' => 'Entrez votre adresse email',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    // new NotBlank(['message' => 'Veuillez entrer l\'adresse email du client.']),
                    new Email(['message' => 'L\'adresse email "{{ value }}" n\'est pas valide.'])
                ]
            ])
            ->add('description', CKEditorType::class, [
                'required' => true,
                'empty_data' => '',
                'label' => 'Description',
                'constraints' => [
                    new Length(['min' => 3, 'minMessage' => 'Le message doit faire au minimum {{ limit }} caractère']),
                    new Assert\NotBlank(['message' => 'Veuillez entrer une description']),
                ]
            ])

            ->add('dateDebut', DateTimeType::class, [
                'required' => true,
                'label' => 'Date et heure du rendez-vous :',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionnez une date et heure de RDV',
                ],
                'html5' => false,
                'constraints' => [
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'Un rendez-vous ne peut pas être placé à une date antérieure !',
                    ]),
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner une date et une heure.'])
                ],
            ])

            ->add('dateFin', DateTimeType::class, [
                'required' => false,
                'label' => 'Fin de rendez-vous prévu :',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionnez une date et heure de fin de RDV',
                ],
                'html5' => false,
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
