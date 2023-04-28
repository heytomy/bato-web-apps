<?php

namespace App\Controller\Admin;

use App\Entity\AppsUtilisateur;
use App\Entity\DefAppsUtilisateur;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use App\Controller\Admin\DefAppsUtilisateurCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\{FormBuilderInterface, FormEvent, FormEvents};
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, EmailField, TextField};
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};

class UtilisateurCrudController extends AbstractCrudController
{
    public function __construct(
        private EntityRepository $entityRepo,
        public UserPasswordHasherInterface $userPasswordHasher
    ) {}
    
    public static function getEntityFqcn(): string
    {
        return AppsUtilisateur::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_NEW, Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ;
    }

    public function configureFields(string $pageName): iterable
    {

        $fields = [
        AssociationField::new('ID_Utilisateur')->hideOnForm()->hideOnIndex()
        ->setCrudController(DefAppsUtilisateurCrudController::class)
        ->autocomplete(),

            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('Nom_Utilisateur'),
            ChoiceField::new('roles')
            ->allowMultipleChoices()
            ->renderAsBadges([
                'ROLE_ADMIN' => 'danger',
                'ROLE_USER' => 'success',
                'ROLE_GESTION' => 'warning',
                'ROLE_TECH_SAV' => 'success',
                'ROLE_TECH_CHANTIER' => 'success',
            ])
            ->setChoices([
                'Utilisateur' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN',
                'Technicien SAV' => 'ROLE_TECH_SAV',
                'Technicien Chantier' => 'ROLE_TECH_CHANTIER',
                'Gestion' => 'ROLE_GESTION',
            ]),
            TextField::new('ID_Utilisateur.Nom', 'Nom'),
            TextField::new('ID_Utilisateur.Prenom', 'Prénom'),
            TextareaField::new('ID_Utilisateur.Adresse', 'Adresse'),
            TextField::new('ID_Utilisateur.CP', 'Code Postal'),
            TextField::new('ID_Utilisateur.Ville', 'Ville'),
            TelephoneField::new('ID_Utilisateur.Tel_1', 'Tel-1'),
            TelephoneField::new('ID_Utilisateur.Tel_2', 'Tel-2'),
            EmailField::new('ID_Utilisateur.Mail', 'E-Mail'),
            BooleanField::new('is_verified'),
        ];

        $password = TextField::new('Mot_de_passe')
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répétez le mot de passe'],
            ])
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms()
            ;

        $fields[] = $password;

        return $fields;
    }

    public function createEntity(string $entityFqcn)
    {
        $user = new DefAppsUtilisateur();
        return $user;
    }

    
    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof DefAppsUtilisateur) return;

        parent::persistEntity($em, $entityInstance);
    }

    public function deleteEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof DefAppsUtilisateur) return;

        foreach ($entityInstance->getComptes() as $user) {
            $em->remove($user);
        }

        parent::deleteEntity($em, $entityInstance);
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword() {
        return function($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('Mot_de_passe')->getData();
            if ($password === null) {
                return;
            } 

            $hash = $this->userPasswordHasher->hashPassword(new AppsUtilisateur, $password);
            $form->getData()->setPassword($hash);

        };
    }
}