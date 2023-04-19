<?php

namespace App\Form;

use App\Entity\AppsUtilisateur;
use App\Entity\DefAppsUtilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, EmailType, TextType};
use Symfony\Component\Form\Extension\Core\Type\{RepeatedType, PasswordType};
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_utilisateur', TextType::class, [
                'required' => true,
                'label' => 'Votre nom d\'utilisateur',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un nom d\'utilisateur',
                ],
            ])

            ->add('ID_Utilisateur', EntityType::class, [
                'class' => DefAppsUtilisateur::class,
                'required' => true,
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Veuillez un nom',
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => true,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Enter a password',
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => [
                        'placeholder' => 'Repeat your password',
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
            ])

            ->add('ID_Utilisateur', TextType::class, [
                'required' => true,
                'label' => 'Votre adresse email',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse email ici',
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
