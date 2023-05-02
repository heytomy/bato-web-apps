<?php

namespace App\Controller\Admin;

use App\Entity\AppsUtilisateur;
use Doctrine\ORM\EntityManager;
use App\Entity\DefAppsUtilisateur;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
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
    ) {}
    
    public static function getEntityFqcn(): string
    {
        return AppsUtilisateur::class;
    }

    public function createEntity(string $entityFqcn)
    {
        // $appsUser = new AppsUtilisateur();
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
        
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('Nom_utilisateur'),
            ChoiceField::new('roles')
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
            TextField::new('ID_Utilisateur.Nom', 'Nom'),
            TextField::new('ID_Utilisateur.Prenom', 'Prénom'),
            TextareaField::new('ID_Utilisateur.Adresse', 'Adresse'),
            TextField::new('ID_Utilisateur.CP', 'Code Postal'),
            TextField::new('ID_Utilisateur.Ville', 'Ville'),
            TelephoneField::new('ID_Utilisateur.Tel_1', 'Tel-1'),
            TelephoneField::new('ID_Utilisateur.Tel_2', 'Tel-2'),
            EmailField::new('ID_Utilisateur.Mail', 'E-Mail'),
            BooleanField::new('is_verified'),
            ColorField::new('colorCode'),
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

    public function new(AdminContext $context)
    {   

        $appsUser = new AppsUtilisateur();
        $form = $this->createForm(RegistrationFormType::class, $appsUser);
        $form->handleRequest($context->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $DefAppsUser = new DefAppsUtilisateur();
            $DefAppsUser
                ->setPrenom($form->get('Prenom')->getData())
                ->setNom($form->get('Nom')->getData())
                ->setAdresse($form->get('Adresse')->getData())
                ->setCP($form->get('CP')->getData())
                ->setVille($form->get('Ville')->getData())
                ->setMail($form->get('Mail')->getData())
                ->setTel1($form->get('Tel_1')->getData())
                ->setTel2($form->get('Tel_2')->getData());

            $roles = $form->get('roles')->getData()->toArray();

            dd($form->get('roles'));


            $appsUser
                ->setNomUtilisateur($form->get('Nom_utilisateur')->getData())
                ->setColorCode($form->get('colorCode')->getData())
                ->addRole($roles)
                ->setIsVerified(true)
                ->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $appsUser,
                        $form->get('plainPassword')->getData()
                    )
                )
                ->setIDUtilisateur($DefAppsUser);

            
            $this->em->persist($DefAppsUser);
            $this->em->persist($appsUser);
            $this->em->flush();


            $this->addFlash('success', 'L\'utilisateur à été crée avec succès! ');
            return $this->redirectToRoute('admin');
        }

        return parent::new($context);

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