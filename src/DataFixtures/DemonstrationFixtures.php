<?php

namespace App\DataFixtures;

use App\Entity\Roles;
use Faker\Factory as Faker;
use App\Entity\AppsUtilisateur;
use App\Entity\DefAppsUtilisateur;
use App\Repository\RolesRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DemonstrationFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;
    protected $rolesRepository;

    public function __construct(UserPasswordHasherInterface $encoder, RolesRepository $rolesRepository)
    {
        $this->encoder = $encoder;
        $this->rolesRepository = $rolesRepository;
    }
    
    public function load(ObjectManager $manager): void
    {
        $role = new Roles;
        $role->setLibelle("ROLE_ADMIN");
        $manager->persist($role);

        $role = new Roles;
        $role->setLibelle("ROLE_GESTION");
        $manager->persist($role);

        $role = new Roles;
        $role->setLibelle("ROLE_TECH_SAV");
        $manager->persist($role);

        $role = new Roles;
        $role->setLibelle("ROLE_TECH_CHANTIER");
        $manager->persist($role);

        $manager->flush();

        $faker = Faker::create('fr_FR');

        $client = new DefAppsUtilisateur;
        $client
            ->setNom("Admin")
            ->setPrenom("Admin")
            ->setAdresse("10 rue des Admins")
            ->setCP("10000")
            ->setVille("Strasbourg")
            ->setTel1("0711223344")
            ->setMail("admin@admin.com")
            ;
        $manager->persist($client);
 
        $roles = $this->rolesRepository->findAll();

        $user = new AppsUtilisateur;
        $user
            ->setIDUtilisateur($client)
            ->setNomUtilisateur('admin')
            ->setPassword($this->encoder->hashPassword($user, 'admin'))
            ->addRole($roles[0])
            ->setIsVerified(true)
            ;
        $manager->persist($user);

        $password = $this->encoder->hashPassword(new AppsUtilisateur, 'password');
        /**
         * Cette boucle génère 50 fausses utilisateurs grâce au package: Faker
         */
        for($i=0; $i<50; $i++) {
            $client = new DefAppsUtilisateur;
            $client
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setAdresse($faker->address())
                ->setCP($faker->postcode())
                ->setVille($faker->city())
                ->setTel1("011223344")
                ->setMail($faker->email())
                ;
            $manager->persist($client);

            $user = new AppsUtilisateur;
            $user
                ->setIDUtilisateur($client)
                ->setNomUtilisateur($faker->userName())
                ->setPassword($password)
                ->addRole($faker->randomElement($roles))
                ->setIsVerified(true)
                ->setColorCode($faker->hexColor())
                ;
            $manager->persist($user);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['demo_group'];
    }
}
