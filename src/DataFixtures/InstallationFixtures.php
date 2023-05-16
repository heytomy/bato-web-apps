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

class InstallationFixtures extends Fixture implements FixtureGroupInterface
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
        // Partie roles
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

        // Partie user
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
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['installation_group'];
    }
}
