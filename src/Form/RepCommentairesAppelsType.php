<?php

namespace App\Form;

use App\Entity\RepCommentairesAppels;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RepCommentairesAppelsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Veuillez écrire une réponse',
                'attr' => [
                    'class'         => 'form-control mr-sm-2',
                    'autocomplete'  =>  'off',
                    'spellcheck'    =>  'false',
                ],
            ])
            ->add('parentid', HiddenType::class, [
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RepCommentairesAppels::class,
        ]);
    }
}
