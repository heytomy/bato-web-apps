<?php

namespace App\Form;

use App\Entity\AppelsSAV;
use App\Entity\ClientDef;
use App\Repository\ClientDefRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppelsSAVType extends AbstractType
{
    private $contrats;

    public function __construct(ClientDefRepository $contrats)
    {
        $this->contrats = $contrats;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class,[
            'class' => ClientDef::class,
            'choices' => $this->contrats->findAll(),
            'label' => 'Client SAV',
            'choice_label' => function(ClientDef $codeClient){
                return $codeClient->getContrats();
            },
            'placeholder' => 'Client SAV',
            'attr' => [
                'class' => 'form-select'
            ]])
            ->add('contrats')
            ->add('description')
            ->add('rdvDate')
            ->add('rdvHeure')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppelsSAV::class,
        ]);
    }
}
