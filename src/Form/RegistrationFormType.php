<?php

namespace App\Form;

use App\Entity\AppsUtilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_utilisateur', TextType::class, [
                'required' => true,
                'label' => 'Votre nom d\'Utilisateur',
                'attr' => [
                    'placeholder' => 'Veuillez entrer un nom d\'utilisateur',
                ],
            ])

            // ->add('email', EmailType::class, [
            //     'required' => true,
            //     'label' => 'Votre adresse email',
            //     'attr' => [
            //         'placeholder' => 'Entrez votre adresse email ici',
            //     ],
            // ])
            
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions d\'utilisation du site',
                'mapped' => false,
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppsUtilisateur::class,
             // enable/disable CSRF protection for this form
             'csrf_protection' => true,
             // the name of the hidden HTML field that stores the token
             'csrf_field_name' => '_token',
             // an arbitrary string used to generate the value of the token
             // using a different string for each form improves its security
             'csrf_token_id'   => 'task_item',
        ]);
    }
}
