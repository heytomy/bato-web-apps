<?php

namespace App\Form;

use App\Entity\AppsUtilisateur;
use App\Entity\DefAppsUtilisateur;
use App\Entity\Roles;
use App\Repository\RolesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\{RepeatedType, PasswordType};
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, EmailType, TextType, TelType, ColorType};

class RegistrationFormType extends AbstractType
{
    private $rolesRepository;

    public function __construct(
        RolesRepository $rolesRepository)
    {
        $this->rolesRepository = $rolesRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_utilisateur', TextType::class, [
                'required' => true,
                'label' => 'Nom d\'utilisateur',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un nom d\'utilisateur',
                ],
            ])

            ->add('colorCode', ColorType::class, [
                'required' => true,
                'label' => 'Code Couleur',
                'attr' => [
                    'placeholder' => '',
                ],
            ])

            ->add('roles', EntityType::class, [
                'mapped' => false,
                'class' => Roles::class,
                'choices' => $this->rolesRepository->findAll(),
                'required' => true,
                'multiple' => true,
                'label' => 'Rôles',
                'choice_label' => function(Roles $roles){
                    return $roles->getLibelle();
                },
                'attr' => [
                    'placeholder' => 'Choissiez un role pour l\'utilisateur',
                    'class' => 'form form-select',
                ],
            ])

            ->add('Nom', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un nom ',
                ],
            ])

            ->add('Prenom', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un prénom ',
                ],
            ])

            ->add('Adresse', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Veuillez entrer une adresse ',
                ],
            ])

            ->add('CP', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Code Postal',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un code postal ',
                ],
            ])

            ->add('Ville', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Veuillez entrer une ville ',
                ],
            ])

            ->add('Tel_1', TelType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un numéro ',
                ],
            ])

            ->add('Tel_2', TelType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Téléphone 2',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un numéro ',
                ],
            ])

            ->add('Mail', EmailType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un email ',
                ],
            ])

            // ->add('ID_Utilisateur', EntityType::class, [
            //     'required' => true,
            //     'label' => 'Votre nom',
            //     'attr' => [
            //         'placeholder' => 'Veuillez un nom',
            //     ],
            // ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => true,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Entrez le mot de passe',
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => [
                        'placeholder' => 'Confirmez le mot de passe',
                    ],
                    'constraints' => [
                        new Callback([
                            'callback' => function ($value, ExecutionContextInterface $context) use ($builder) {
                                $password = $builder->get('plainPassword')->getData();
                                $confirmPassword = $builder->get('plainPassword')->get('second')->getData();
                                if ($password !== $confirmPassword) {
                                    $context->buildViolation('The passwords do not match.')
                                        ->atPath('second')
                                        ->addViolation();
                                }
                            }
                        ])
                    ]
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppsUtilisateur::class,
             'csrf_protection' => true,
             'csrf_field_name' => '_token',
             'csrf_token_id'   => 'task_item',
        ]);
    }
}
