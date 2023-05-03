<?php

namespace App\Controller\Admin;

use App\Entity\AppsUtilisateur;
use App\Entity\DefAppsUtilisateur;
use App\Repository\RolesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\CallbackTransformer;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
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
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};
use EasyCorp\Bundle\EasyAdminBundle\Field\{ColorField, IdField, EmailField, TextField};

class UtilisateurCrudController extends AbstractCrudController
{
    
    public function __construct(
        private EntityRepository $entityRepo,
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $em,
        private RolesRepository $rolesRepository
    ) {}
    
    public static function getEntityFqcn(): string
    {
        return AppsUtilisateur::class;
    }

    public function createEntity(string $entityFqcn)
    {
        // $appsUser = new AppsUtilisateur();
        // $defAppsUser = new DefAppsUtilisateur();
        // $appsUser->setIDUtilisateur($defAppsUser);

        // return $appsUser;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_NEW, Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::EDIT)
            ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des utilisateurs')
            ->setPageTitle('edit', 'Modification de l\'utilisateur')
            ->setPageTitle('new', 'Création d\'un utilisateur');
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
        AssociationField::new('ID_Utilisateur')->hideOnForm()->hideOnIndex()
        ->setCrudController(DefAppsUtilisateurCrudController::class)
        ->autocomplete(),
        
            yield IdField::new('id')->hideOnForm()->hideOnIndex(),
            yield TextField::new('Nom_utilisateur'),
            yield ChoiceField::new('roles')
            ->renderAsBadges([
                'ROLE_ADMIN' => 'danger',
                'ROLE_GESTION' => 'warning',
                'ROLE_TECH_SAV' => 'success',
                'ROLE_TECH_CHANTIER' => 'success',
            ])
            ->setChoices([
                'Administrateur' => 'ROLE_ADMIN',
                'Technicien SAV' => 'ROLE_TECH_SAV',
                'Technicien Chantier' => 'ROLE_TECH_CHANTIER',
                'Gestion' => 'ROLE_GESTION',
            ]),

            yield TextField::new('ID_Utilisateur.Nom', 'Nom'),
            yield TextField::new('ID_Utilisateur.Prenom', 'Prénom'),
            yield TextareaField::new('ID_Utilisateur.Adresse', 'Adresse'),
            yield TextField::new('ID_Utilisateur.CP', 'Code Postal'),
            yield TextField::new('ID_Utilisateur.Ville', 'Ville'),
            yield TelephoneField::new('ID_Utilisateur.Tel_1', 'Tel-1'),
            yield TelephoneField::new('ID_Utilisateur.Tel_2', 'Tel-2'),
            yield EmailField::new('ID_Utilisateur.Mail', 'E-Mail'),
            yield BooleanField::new('is_verified'),
            yield ColorField::new('colorCode'),
        ];

        $password = yield TextField::new('Mot_de_passe')
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

    // public function new(AdminContext $context)
    // {   
    //     $AppsUser = new AppsUtilisateur();
    //     $DefAppsUser = new DefAppsUtilisateur();

    //     $form = $this->createForm($AppsUser);
    //     $form->handleRequest($context->getRequest());

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $selectedRoles = $form->get('roles')->getData();

    //         $AppsUser
    //             ->setNomUtilisateur($form->get('AppsUtilisateur_Nom_utilisateur')->getData())
    //             ->setColorCode($form->get('colorCode')->getData())
    //             ->addRole($selectedRoles)
    //             ->setIsVerified(true)
    //             ->setPassword(
    //                 $this->userPasswordHasher->hashPassword(
    //                     $AppsUser,
    //                     $form->get('plainPassword')->getData()
    //                 )
    //             )
    //             ->setIDUtilisateur($DefAppsUser);

    //         $DefAppsUser
    //             ->setPrenom($form->get('Prenom')->getData())
    //             ->setNom($form->get('AppsUtilisateur_ID_Utilisateur_Nom')->getData())
    //             ->setAdresse($form->get('Adresse')->getData())
    //             ->setCP($form->get('CP')->getData())
    //             ->setVille($form->get('Ville')->getData())
    //             ->setMail($form->get('Mail')->getData())
    //             ->setTel1($form->get('Tel_1')->getData())
    //             ->setTel2($form->get('Tel_2')->getData());

            
    //         $this->em->persist($DefAppsUser);
    //         $this->em->persist($AppsUser);
    //         $this->em->flush();


    //         $this->addFlash('success', 'L\'utilisateur à été crée avec succès! ');
    //         return $this->redirectToRoute('admin');
    //     }

    //     return parent::new($context);

    // }
    
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