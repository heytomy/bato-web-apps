<?php

namespace App\Form;

use App\Entity\ChantierApps;
use App\Entity\ClientDef;
use App\Entity\StatutChantier;
use App\Repository\ClientDefRepository;
use App\Repository\StatutChantierRepository;
use Symfony\Component\Form\AbstractType;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChantierAppsType extends AbstractType
{
    protected $statutChantierRepository;
    protected $clientDefRepository;

    public function __construct(
        StatutChantierRepository $statutChantierRepository,
        ClientDefRepository $clientDefRepository,
        )
    {
        $this->statutChantierRepository = $statutChantierRepository;
        $this->clientDefRepository = $clientDefRepository;
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
                'label'         =>  'Date de début du chantier',
                'widget'        =>  'single_text',
                'attr'          =>  [
                    'class'         =>  'form-control   datetimepicker',
                    'placeholder'   =>  'veuillez indiquer la date de début du chantier',
                ],
                'html5'         =>  true,
                'constraints'   =>  [
                    new Assert\NotBlank()
                ],
            ])
            ->add('dateFin', DateType::class, [
                'required'      =>  true,
                'label'         =>  'Date de fin du chantier',
                'widget'        =>  'single_text',
                'attr'          =>  [
                    'class'         =>  'form-control   datetimepicker',
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
