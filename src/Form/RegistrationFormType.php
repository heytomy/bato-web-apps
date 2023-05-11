<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\AppsUtilisateur;
use App\Repository\RolesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\{RepeatedType, PasswordType};
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, EmailType, TextType, TelType, ColorType};
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    private $rolesRepository;

    public function __construct(
        RolesRepository $rolesRepository
    ) {
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
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('colorCode', ColorType::class, [
                'required' => true,
                'label' => 'Code Couleur',
                'attr' => [
                    'placeholder' => 'Veuillez choisir une couleur',
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('roles', EntityType::class, [
                'mapped' => false,
                'class' => Roles::class,
                'choices' => $this->rolesRepository->findAll(),
                'required' => true,
                'multiple' => false,
                'label' => 'Rôles',
                'choice_label' => function (Roles $roles) {
                    return $roles->getLibelle();
                },
                'attr' => [
                    'placeholder' => 'Choisissiez un role pour l\'utilisateur',
                    'class' => 'form form-select mb-3',
                ],
            ])

            ->add('Nom', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un nom ',
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('Prenom', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un prénom ',
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('Adresse', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Veuillez entrer une adresse ',
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('CP', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Code Postal',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un code postal ',
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('Ville', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Veuillez entrer une ville ',
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('Tel_1', TelType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un numéro ',
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('Tel_2', TelType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Téléphone 2',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un numéro ',
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('Mail', EmailType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un email ',
                    'class' => 'form-control mb-3',
                ],
            ])

            // ->add('ID_Utilisateur', EntityType::class, [
            //     'required' => true,
            //     'label' => 'Votre nom',
            //     'attr' => [
            //         'placeholder' => 'Veuillez un nom',
            //     ],
            // ])

            // ->add('plainPassword', RepeatedType::class, [
            //     'type' => PasswordType::class,
            //     'mapped' => false,
            //     'required' => true,
            //     'first_options' => [
            //         'label' => 'Password',
            //         'attr' => [
            //             'placeholder' => 'Entrez le mot de passe',
            //             'class' => 'form-control',
            //         ],
            //         'constraints' => [
            //             new Assert\NotBlank(),
            //             new Assert\Regex([
            //                 'pattern' => '/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/',
            //                 'message' => 'Votre mot de passe doit comporter au moins 8 caractères, une lettre majuscule et un chiffre.',
            //             ]),
            //         ],
            //     ],
            //     'second_options' => [
            //         'label' => 'Repeat Password',
            //         'attr' => [
            //             'placeholder' => 'Confirmez le mot de passe',
            //             'class' => 'form-control',
            //         ],
            //         'constraints' => [
            //             new Callback([
            //                 'callback' => function ($value, ExecutionContextInterface $context) use ($builder) {
            //                     $password = $builder->get('plainPassword')->getData();
            //                     $confirmPassword = $builder->get('plainPassword')->get('second')->getData();
            //                     if ($password !== $confirmPassword) {
            //                         $context->buildViolation('Les mots de passes ne correspondent pas.')
            //                             ->atPath('second')
            //                             ->addViolation();
            //                     }
            //                 }
            //             ])
            //         ]
            //     ]
            // ]);

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne correspondent pas.',
                'required' => true,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez entrer un mot de passe',
                        'class' => 'form-control',
                    ],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Le mot de passe ne doit pas être vide.',
                        ]),
                        new Assert\Regex([
                            'pattern' => '/^(?=.*[A-Z])(?=.*\d).{8,}$/',
                            'message' => 'Le mot de passe doit comporter au moins 8 caractères, une lettre majuscule et un chiffre.',
                        ]),
                    ],
                    
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez confirmer votre mot de passe',
                        'class' => 'form-control',
                    ],
                ],
                'constraints' => [
                    new Callback(function ($data, ExecutionContextInterface $context) {
                        if (is_array($data)) { // Check if $data is an array
                            $password = $data['first'];
                            $confirmPassword = $data['second'];
                            if ($password !== $confirmPassword) {
                                $context->buildViolation('Les mots de passes ne correspondent pas.')
                                    ->atPath('second')
                                    ->addViolation();
                            }
                        }
                    })
                ],                
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
