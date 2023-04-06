<?php

namespace App\Form;

use App\Entity\CommentairesAppels;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentairesAppelsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Veuillez écrire un commentaire',
                'attr' => [
                    'placeholder'   => 'Veuillez écrire un commentaire',
                    'class'         => 'form-control mr-sm-2',
                    'autocomplete'  =>  'off',
                    'spellcheck'    =>  'false',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentairesAppels::class,
        ]);
    }
}
