<?php

namespace App\Form;

use App\Entity\ChantierApps;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChantierTermineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('description', CKEditorType::class, [
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
