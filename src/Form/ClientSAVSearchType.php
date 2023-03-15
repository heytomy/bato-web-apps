<?php

namespace App\Form;

use App\Entity\savSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class savSearchType extends AbstractType
{
    public $routeGenerator;

    public function __construct(UrlGeneratorInterface $routeGenerator)
    {
        $this->routeGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => savSearch::class,
            'method' => 'get', // lors de la soumission du formulaire, les paramètres transiteront dans l'url. Utile pour partager la recherche par exemple
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        // permet d'enlever les préfixe dans l'url. Tu peux commenter cette fonction, soumettre le formulaire et regarder l'url pour voir la différence.
        return '';
    }
}