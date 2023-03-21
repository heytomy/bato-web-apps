<?php

namespace App\Form;

use App\Entity\RepCommentairesSAV;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RepCommentairesSAVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire_SAV', TextareaType::class, [
                'label' => 'Veuillez ecrire une réponse',
                'attr' => [
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
            'data_class' => RepCommentairesSAV::class,
        ]);
    }
}
