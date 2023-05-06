<?php

namespace App\Form;

use App\Entity\ClientDef;
use App\Entity\ChantierApps;
use App\Entity\StatutChantier;
use App\Entity\AppsUtilisateur;
use App\Repository\ClientDefRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\StatutChantierRepository;
use App\Repository\AppsUtilisateurRepository;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ChantierAppsType extends AbstractType
{
    protected $statutChantierRepository;
    protected $clientDefRepository;
    private $roles;

    public function __construct(
        StatutChantierRepository $statutChantierRepository,
        ClientDefRepository $clientDefRepository,
        AppsUtilisateurRepository $roles, 
        )
    {
        $this->statutChantierRepository = $statutChantierRepository;
        $this->clientDefRepository = $clientDefRepository;
        $this->roles = $roles;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('statut', EntityType::class, [
            //     'required'      =>  false,
            //     'class'         =>  StatutChantier::class,
            //     'choices'       =>  $this->statutChantierRepository->findAll(),
            //     'label'         =>  'Statut du chantier',
            //     'choice_label'  =>  function (StatutChantier $statutChantier) {
            //         return $statutChantier->getStatut();
            //     },
            //     'placeholder'   =>  'Choisissez le statut du chantier',
            //     'attr'          =>  [
            //         'class'             =>  'form-select',
            //     ],
            // ])
            ->add('ID_Utilisateur', EntityType::class, [
                'required' => true,
                'class' => AppsUtilisateur::class,
                'choices' => $this->roles->findByRoleTech('ROLE_TECH_CHANTIER'),
                'label' => 'Technicien',
                'choice_label' => function (AppsUtilisateur $username) {
                    return $username->getIDUtilisateur()->getNom() . ' ' . $username->getIDUtilisateur()->getPrenom();
                },
                'placeholder' => 'Choisissez un technicien',
                'attr' => [
                    'class' => 'form-select'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un technicien'])
                ]
            ])
            ->add('codeClient', EntityType::class, [
                'required'      =>  true,
                'class'         =>  ClientDef::class,
                'choices'       =>  $this->clientDefRepository->findAll(),
                'label'         =>  'Clients',
                'choice_label'  =>  function (ClientDef $client) {
                    return $client->getNom();
                },
                'placeholder'   =>  'Choisissez le client',
                'attr'          =>  [
                    'class'             =>  'form-select',
                ]
            ])
            ->add('libelle', TextType::class, [
                'required'      =>  true,
                'label'         =>  'Libellé',
                'empty_data'    =>  null,
                'attr'          =>  [
                    'placeholder'   =>  'Libellé du chantier',
                    'class'         =>  'form-control',
                ],
                'constraints'   =>  [
                    new Assert\NotBlank(['message' => 'Veuillez saisir un Libellé']),
                    new Assert\Length(['max' => 50, 'maxMessage' => 'Le libellé ne doit pas dépasser {{ limit }} caractères']),
                ],
            ])
            ->add('Tel', TelType::class, [
                'mapped' => false, // le temps d'update la db
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
                'mapped' => false, // le temps d'update la db
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
            ->add('adresse', TextType::class, [
                'required'      =>  true,
                'label'         =>  'Adresse',
                'empty_data'    =>  null,
                'attr'          =>  [
                    'placeholder'   =>  'Adresse du chantier',
                    'class'         =>  'form-control',
                ],
                'constraints'   =>  [
                    new Assert\NotBlank(['message' => 'Veuillez saisir une adresse']),
                    new Assert\Length(['max' => 100, 'maxMessage' => 'L\'adresse ne doit pas dépasser {{ limit }} caractères']),
                ],
            ])
            ->add('cp', TextType::class, [
                'required'      =>  true,
                'label'         =>  'Code postal',
                'empty_data'    =>  null,
                'attr'          =>  [
                    'placeholder'   =>  'Code postal',
                    'class'         =>  'form-control',
                ],
                'constraints'   =>  [
                    new Assert\NotBlank(['message' => 'Veuillez saisir un code postal']),
                    new Assert\Regex(['pattern' => '/^\d{5}$/i', 'message' => 'Le code postal doit être composé de 5 chiffres']),
                ],
            ])
            ->add('ville', TextType::class, [
                'required' => true,
                'label' => 'Ville',
                'empty_data' => null,
                'attr' => [
                    'fieldset' => false,
                    'placeholder' => 'Ville',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir une ville']),
                    new Assert\Length(['max' => 100, 'maxMessage' => 'La ville ne doit pas dépasser {{ limit }} caractères']),
                ],
            ])
            ->add('dateDebut', DateType::class, [
                'required'      =>  true,
                // 'format'        => 'dd-mm-YY',
                'label'         =>  'Date de début du chantier',
                'widget'        =>  'single_text',
                'attr'          =>  [
                    'class'         =>  'form-control',
                    'placeholder'   =>  'veuillez indiquer la date de début du chantier',
                ],
                'html5'         =>  true,
                'constraints'   =>  [
                    new Assert\NotBlank()
                ],
            ])
            ->add('dateFin', DateType::class, [
                'required'      =>  true,
                // 'format'        => 'dd-mm-YY',
                'label'         =>  'Date de fin du chantier',
                'widget'        =>  'single_text',
                'attr'          =>  [
                    'class'         =>  'form-control',
                    'placeholder'   =>  'veuillez indiquer la date de fin du chantier',
                ],
                'html5'         =>  true,
                'constraints'   =>  [
                    new Assert\NotBlank()
                ],
            ])
            ->add('description', TinymceType::class, [
                'required'      =>  true,
                'label'         =>  'Description',
                'attr'          =>  [
                    'selector'      =>  'textarea',
                    'toolbar'       =>  'undo redo | copy cut paste',
                    'menubar'       =>  'false',
                    'contextmenu'   =>  'false',
                    'placeholder'   =>  'Veuillez décrire le chantier'
                ],
                'constraints'   =>  [
                    new Assert\NotBlank(['message' => 'Veuillez entrer une description du chantier']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChantierApps::class,
        ]);
    }
}
